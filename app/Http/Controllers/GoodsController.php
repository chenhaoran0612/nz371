<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use ElfSundae\Laravel\Hashid\Facades\Hashid;
use App\User;
use App\GoodsBasic;
use Validator;
use App\OperationLog;
use App\GoodsOnline;
use App\GoodsCategory;
use App\GoodsBasicHistory;
use App\GoodsOnlineHistory;

class GoodsController extends Controller
{
    const REQUIRED = '*为必填项';
    const NUMERIC = '内容必须为数字类型';
    const OPERATE_SUCCESS = '操作成功';
    const AUTH_LOWER = '未找到对应信息或权限不足';
    const PUSHFORWARD = '改信息已推送,请勿重复提交';
    const SKUREPLACE = 'SKU重复';
    const NOCOMPLETE = '请将商品信息填写完整,再进行该操作';

    //供应商商品列表
    public function goodsBasic(Request $request)
    {
        $user = Auth::user();
        if (!$user->isVendor()) {
            return back();
        }
        
        $searchs = $request->only('title', 'id');
        $searchs['id'] = Hashid::decode($request->id);
        $conditions = [
            'title' => 'like',
            'id' => '='
        ];
        $data['goodsBasics'] = GoodsBasic::search($searchs, $conditions)->vendor($user)->orderBy('id','desc')->paginate(20);
        $data['goodsBasics']->appends(array_filter($request->all()));
        $data['newMap'] = GoodsBasic::newMap();
        $data['currency'] = $user->getPaymentMethod->currency;
        return view('goods.goods_basic', $data);
    }

    public function goodsbasicCreate(Request $request)
    {
        $data['user'] = Auth::user();
        $data['newMap'] = GoodsBasic::newMap();
        return view('goods.goods_basic_create', $data);
    }

    public function goodsBasicCreateSave(Request $request)
    {
        $user = Auth::user();
        if (!$user->isVendor()) {
            return ['result' => false, 'message' => self::AUTH_LOWER];
        }

        $data = $request->all();
        $message = $this->goodsBasicValidator($data);
        if (is_array($message)) {
            return ['result' => false, 'message'=> $message];
        }
        $data['user_id'] = $user->getParentId();
        $data['vendor_id'] = $user->id;
        $data['currency'] = $user->getPaymentMethod->currency;
        GoodsBasic::create($data);
        OperationLog::createFromRequest($request);
        return ['result' => true, 'message' => self::OPERATE_SUCCESS];
    }

    public function goodsBasicValidator($data){
        $validate = 
        [
            'title' => 'required|max:255',
            'price' => 'required|numeric',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',

        ];
        $message = 
        [
            'required' => self::REQUIRED,
            'numeric' => self::NUMERIC,
        ];
        $validator = Validator::make($data, $validate, $message);
        if ($validator->fails()) {
            return $validator->errors()->toArray();
        }
        return true;
    }

    public function getGoodsImage(Request $request)
    {
        $user = Auth::user();
        $id = Hashid::decode($request->id);
        if (!$id) {
            return [];
        }
        $images = GoodsBasic::whereId($id)->pluck('images')->first();
        if (!$images) {
            return [];
        }
        $images = explode(',', $images);
        foreach ($images as $key => $image) {
            $result[] = [
                'url' => $image,
                'thumbnailUrl' => $image,
                'name' => $image,
            ];
        }
        return ['files' => $result];
    }

    public function goodsbasicEdit(Request $request)
    {
        $user = Auth::user();
        $id = Hashid::decode($request->id);

        if (!$id || !$goodsBasic = GoodsBasic::vendor($user)->whereId($id)->first()) {
            return back();
        }
        $data['user'] = $user;
        $data['newMap'] = GoodsBasic::newMap();
        $data['goods'] = $goodsBasic;
        return view('goods.goods_basic_edit', $data);
    }

    public function goodsbasicEditSave(Request $request)
    {
        $user = Auth::user();
        if (!$user->isVendor()) {
            return ['result' => false, 'message' => self::AUTH_LOWER];
        }
        $data = $request->all();
        $data['id'] = Hashid::decode($request->id);
        $message = $this->goodsBasicValidator($data);
        if (is_array($message)) {
            return ['result' => false, 'message'=> $message];
        }

        if (!$goodsBasic = GoodsBasic::vendor($user)->whereId($data['id'])->first()) {
            return ['result' => false, 'message' => self::AUTH_LOWER];
        }

        //在线商品下架
        $goodsOnline = GoodsOnline::where('goods_basic_id', $goodsBasic->id)->online()->first();
        if ($goodsOnline) {
            $goodsOnline->offOnline();
            $GoodsBasic->resetStatus();
        }

        $goodsBasic->update($data);
        OperationLog::createFromRequest($request);
        return ['result' => true, 'message' => self::OPERATE_SUCCESS];
    }

    /**
     * 经销商商品推送
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function goodsbasicPush(Request $request)
    {
        $user = Auth::user();
        if (!$user->isVendor()) {
            return ['result' => false, 'message' => self::AUTH_LOWER];
        }
        $id = Hashid::decode($request->id);
        $goodsBasic = GoodsBasic::whereId($id)->vendor($user)->first();
        if (!$goodsBasic) {
            return ['result' => false, 'message' => self::AUTH_LOWER];
        }
        //推送状态
        $goodsBasic->push();
        //历史记录
        $goodsBasicHistory = GoodsBasicHistory::createGoodsBasicHistory($goodsBasic);

        $goods = $goodsBasic->toArray();
        $data = [
            'goods_basic_id' => $goodsBasic->id,
            'goods_basic_history_id' => $goodsBasicHistory->id,
        ];
        unset($goods['id']);
        unset($goods['created_at']);
        unset($goods['updated_at']);
        unset($goods['push_status']);
        $goods['attribute']  = json_encode($goods['attribute']);
        $goods['online_status'] = 0;
        $goods = array_merge($goods, $data);
        $goodsOnline = GoodsOnline::where('goods_basic_id', $goodsBasic->id)->first();
        if ($goodsOnline) {
        	$goodsOnline->rePush();
            $goodsOnline->update($goods);
        } else {
        	GoodsOnline::create($goods);
        }
        OperationLog::createFromRequest($request);
        return ['result' => true, 'message' => self::OPERATE_SUCCESS];
    }

    //管理员商品列表
    public function goodsOnline(Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            return back();
        }
        
        $searchs = $request->only('title', 'id', 'vendor_id');
        $searchs['id'] = Hashid::decode($request->id);
        $searchs['vendor_id'] = Hashid::decode($request->vendor_id);
        $conditions = [
            'title' => 'like',
            'id' => '=',
            'vendor_id' => '='
        ];
        $data['goodsOnline'] = GoodsOnline::search($searchs, $conditions)->user($user)->orderBy('updated_at','desc')->paginate(20);
        $data['goodsOnline']->appends(array_filter($request->all()));
        $data['vendors'] = User::user($user)->vendor()->get();
        $data['newMap'] = GoodsBasic::newMap();
        return view('goods.goods_online', $data);
    }

    public function goodsonlineEdit(Request $request)
    {
        $user = Auth::user();
        $id = Hashid::decode($request->id);
        if (!$id || !$goodsOnline = GoodsOnline::whereId($id)->user($user)->first()) {
            return back();
        }
        $data['newMap'] = GoodsBasic::newMap();
        $data['goods'] = $goodsOnline;
        $data['id'] = $goodsOnline->id;
        //重新提交
        if ($goodsOnline->repush) {
            $goodsOnlineHistory = GoodsOnlineHistory::where('goods_online_id', $goodsOnline->id)->orderBy('id', 'desc')->first()->toArray();
            $goodsOnlineHistory['attribute'] = json_encode($goodsOnlineHistory['attribute']);
            $online = $goodsOnline->toArray();
            $online['attribute'] = json_encode($online['attribute']);
        	$data['diff'] = array_diff($goodsOnlineHistory, $online);
            if (isset($data['diff']['price'])) {
                $history = GoodsBasicHistory::whereId($goodsOnlineHistory['goods_basic_history_id'])->first();
                $data['diff']['original_price'] = $history->price;
            }
            if (isset($data['diff']['images'])) {
                $data['diff']['images'] = explode(',', $data['diff']['images']);
            }
            if (isset($data['diff']['attribute'])) {
                $data['diff']['attribute'] = json_decode($data['diff']['attribute'], true);
            }
        }
        // dd($data['diff']);
        $data['categories'] = GoodsCategory::user($user)->get();
        return view('goods.goods_online_edit', $data);
    }

    public function getGoodsOnlineImage(Request $request)
    {
        $user = Auth::user();
        $id = Hashid::decode($request->id);
        if (!$id) {
            return [];
        }
        $images = GoodsOnline::whereId($id)->user($user)->pluck('images')->first();
        if (!$images) {
            return [];
        }
        $images = explode(',', $images);
        foreach ($images as $key => $image) {
            $result[] = [
                'url' => $image,
                'thumbnailUrl' => $image,
                'name' => $image,
            ];
        }
        return ['files' => $result];
    }

    public function goodsonlineEditSave(Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            return ['result' => false, 'message' => self::AUTH_LOWER];
        }
        $data = $request->all();
        $data['id'] = Hashid::decode($request->id);

        $message = $this->goodsOnlineValidator($data, $user);
        if (is_array($message)) {
            return ['result' => false, 'message'=> $message];
        }
        if (!$goodsOnline = GoodsOnline::whereId($data['id'])->user($user)->first()) {
            return ['result' => false, 'message' => self::AUTH_LOWER];
        }
        $data['repush'] = 0;
        $goodsOnline->update($data);
        OperationLog::createFromRequest($request);
        return ['result' => true, 'message' => self::OPERATE_SUCCESS];
    }

    public function goodsOnlineValidator($data, $user){
        $validate = 
        [
            'title' => 'required|max:255',
            'price' => 'required|numeric',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'item_number' => 'required|unique:goods_onlines,item_number,'.$data['id'].',id',',deleted_at,null,user_id,'.$user->getParentId(),
            'category_id' => 'required|numeric',
        ];
        $message = 
        [
            'required' => self::REQUIRED,
            'numeric' => self::NUMERIC,
            'unique' => self::SKUREPLACE,
        ];
        $validator = Validator::make($data, $validate, $message);
        if ($validator->fails()) {
            return $validator->errors()->toArray();
        }
        return true;
    }

    public function goodsOnlineStatus(Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            return ['result' => false, 'message' => self::AUTH_LOWER];
        }
        $id = Hashid::decode($request->id);
        $goodsOnline = GoodsOnline::whereId($id)->user($user)->first();
        if (!$goodsOnline) {
            return ['result' => false, 'message' => self::AUTH_LOWER];
        }
        if (!$goodsOnline->item_number || $goodsOnline->repush) {
        	return ['result' => false, 'message' => self::NOCOMPLETE];
        }
        $goodsOnline->online_status = $request->status;
        $goodsOnline->save();

        GoodsOnlineHistory::createGoodsOnlineHistory($goodsOnline);
        OperationLog::createFromRequest($request);
        return ['result' => true, 'message' => self::OPERATE_SUCCESS];
    }

    public function goodsCategory(Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            return back();
        }
        $searchs = $request->only('name', 'id');
        $searchs['id'] = Hashid::decode($request->id);
        $conditions = [
            'name' => 'like',
            'id' => '='
        ];
        $data['goodsCategory'] = GoodsCategory::search($searchs, $conditions)->user($user)->orderBy('id','desc')->paginate(20);
        $data['goodsCategory']->appends(array_filter($request->all()));
        return view('goods.goods_category', $data);
    }

    public function goodsCategorycreate(Request $request)
    {
        $data['user'] = Auth::user();
        return view('goods.goods_category_create');
    }

    public function goodsCategoryCreateSave(Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            return ['result' => false, 'message' => self::AUTH_LOWER];
        }
        $data = $request->all();
        $message = $this->goodsCategoryValidator($data);
        if (is_array($message)) {
            return ['result' => false, 'message'=> $message];
        }
        $data['user_id'] = $user->getParentId();
        GoodsCategory::create($data);
        OperationLog::createFromRequest($request);
        return ['result' => true, 'message' => self::OPERATE_SUCCESS];
        
    }

    public function goodsCategoryValidator($data){
        $validate = 
        [
            'name' => 'required|max:255',
        ];
        $message = 
        [
            'required' => self::REQUIRED,
        ];
        $validator = Validator::make($data, $validate, $message);
        if ($validator->fails()) {
            return $validator->errors()->toArray();
        }
        return true;
    }

    public function goodsCategoryEdit(Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            return back();
        }
        $id = Hashid::decode($request->id);
        $data['goodsCategory'] = GoodsCategory::whereId($id)->user($user)->first();
        if (!$data['goodsCategory']) {
            return back();
        }
        return view('goods.goods_category_edit', $data);
    }

    public function goodsCategoryEditSave(Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            return back();
        }

        $data = $request->all();
        $message = $this->goodsCategoryValidator($data);
        if (is_array($message)) {
            return ['result' => false, 'message'=> $message];
        }

        $id = Hashid::decode($request->id);
        $goodsCategory = GoodsCategory::whereId($id)->user($user)->first();
        if (!$goodsCategory) {
            return back();
        }
        $goodsCategory->name = $request->name;
        $goodsCategory->save();
        return ['result' => true, 'message' => self::OPERATE_SUCCESS];
    }



}
