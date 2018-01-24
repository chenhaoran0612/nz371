<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Services\Qiniu;
use Validator;
use DB;
use App\User;
use App\UserGroup;
use App\TransactionLog;
use App\PaymentMethod;
use App\Services\Excel\DownloadService;
use App\Services\Excel\UploadService;
use ElfSundae\Laravel\Hashid\Facades\Hashid;
use App\UserAccount;
use App\OperationLog;


class UserController extends Controller
{
    const REQUIRED = '*为必填项';
    const PASSWORD_SAME = '两次密码输入不一致';
    const PASSWORD_BETWEEN = '密码必须是6~20位之间';
    const OLD_PASSWORD_ERROR = '原密码错误';
    const EMAIL_ERROR = 'EMAIL格式错误';
    const EMAIL_EXIST = 'Email已存在';
    const OPERATE_SUCCESS = '操作成功';
    const OPERATE_FAIL = '操作失败';
    const AUTH_LOWER = '未找到对应信息或权限不足';
    const DELETE_SUCCESS = "删除成功";
    const GROUP_NULL = "用户组不能为空";
    const GROUP_REPEAT = "用户组重复";
    const NOT_EMPTY = '文件不能为空';
    const MIMES_ERROR = '上传文件类型只允许xls,xlsx';
    const VERIFY_MESSAGE_SUCCESS = '获取验证码成功';
    const VERIFY_MESSAGE_FAIL = '获取验证码失败';
    const CHAR_LONG = '字符串过长';

    //经销商
    function distributor(Request $request)
    {
        
        $user =Auth::user();
        $searchs = $request->only('user_code', 'name', 'level', 'credit', 'status');
        $conditions = [
            'user_code' => '=',
            'name' => 'like',
            'level' => '=',
            'credit' => '=',
            'status' => '=',
        ];
        $data['users'] = User::user($user)->search($searchs, $conditions)->distributor()->orderBy('id')->paginate(20);
        $data['users']->appends(array_filter($request->all()));
        return view('user.distributor', $data);
    }

    //经销商
    function distributorCreate(Request $request)
    {
        $user =Auth::user();
        return view('user.distributor_create');
    }

    //经销商创建
    function distributorCreateSave(Request $request)
    {
        $user = Auth::user();
        $userId = $user->getParentId();

        if ($user->isVnedorOrDistributor()) {
            return ['result' => false, 'message' => self::AUTH_LOWER];
        }

        $data = $request->all();
        $message = $this->userValidator($data);
        if (is_array($message)) {
            return ['result' => false, 'message'=>$message];
        }
        $data['status'] = $data['status']=='true'? 1: 0;
        $data['role'] = 'distributor';
        $data['parent_id'] = $userId;
        $data['logo'] = explode(',',$data['logo'])[0];
        $data['password'] = bcrypt($data['password']);
        $distributor = User::create($data);
        $distributor->setUserCode();

        UserAccount::createAccountByUser($distributor);

        $request->request->remove('password');
        $request->request->remove('comfirm_password');
        OperationLog::createFromRequest($request);
        return ['result' => true, 'message' => self::OPERATE_SUCCESS];
    }

    public function userValidator($data){
        $validate = 
        [
            'name' => 'required|max:255',
            'password' => 'sometimes|same:comfirm_password|between:6,20',
            'email' => 'sometimes|required|email|max:255|unique:users,email,'.(isset($data['id']) ? $data['id'] : 'null').',id',
            'qualification' => 'sometimes|required|max:11',
            'payment_method_id' => 'sometimes|required|max:11',

        ];
        $message = 
        [
            'required' => self::REQUIRED,
            'same' => self::PASSWORD_SAME,
            'between' => self::PASSWORD_BETWEEN,
            'email' => self::EMAIL_ERROR,
            'unique' => self::EMAIL_EXIST,
            'max' => self::CHAR_LONG,
        ];
        $validator = Validator::make($data, $validate, $message);
        if ($validator->fails()) {
            return $validator->errors()->toArray();
        }
        return true;
    }

    public function distributorEdit(Request $request)
    {
        $user = Auth::user();
        $id = Hashid::decode($request->id);

        if (!$id || !$userEdit = User::user($user)->whereId($id)->first()) {
            return back();
        }
        $data['user'] = $userEdit;
        return view('user.distributor_edit', $data);
    }

    //经销商编辑保存
    public function distributorEditSave(Request $request)
    {
        $data = $request->all();
        $data['id'] = Hashid::decode($data['id']);
        $data = array_filter($data);
        $message = $this->userValidator($data);
        if (is_array($message)) {
            return ['result' => false, 'message'=>$message];
        }

        //验证用户权限
        $user = Auth::user();
        $data['status'] = $data['status']=='true'? 1: 0;
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
            unset($data['comfirm_password']);
        }
        $effectNumber = User::whereId($data['id'])->user($user)->update($data);

        $request->request->remove('password');
        $request->request->remove('comfirm_password');
        OperationLog::createFromRequest($request);
        if ($effectNumber) {
            return ['result' => true, 'message' => self::OPERATE_SUCCESS];
        } else {
            return ['result' => false, 'message' => self::OPERATE_FAIL];
        }
    }

    //Logo上传
    public function uploadUserLogo(Request $request)
    {
        $files = $request->file('files');
        foreach ($files as $file) {
            $qiniu = new Qiniu;
            $result = $qiniu->upload($file);
            if ($result['result']) {
                return [
                    'files' => [[
                        'deleteType' => 'DELETE',
                        'thumbnailUrl' => $result['message']['url'],
                        'url' => $result['message']['url'],
                        'type' => $file->getMimeType(),
                        'name' => $result['message']['url'],
                    ]]
                ];
            }
        }
    }

    //Logo获取
    public function getUserLogo(Request $request)
    {
        $user = Auth::user();
        $id = Hashid::decode($request->id);
        if (!$id) {
            return [];
        }
        $logo = User::whereId($id)->user($user)->pluck('logo')->first();
        if (!$logo) {
            return [];
        }
        $result[] = [
            'url' => $logo,
            'thumbnailUrl' => $logo,
            'name' => $logo,
        ];
        return ['files' => $result];
    }

    /**
     * [vendor description] 供应商管理入口
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function vendor(Request $request)
    {
        $user =Auth::user();
        $searchs = $request->only('user_code', 'name', 'level', 'credit', 'status');
        $conditions = [
            'user_code' => '=',
            'name' => 'like',
            'level' => '=',
            'credit' => '=',
            'status' => '=',
        ];
        $data['users'] = User::user($user)->search($searchs, $conditions)->vendor()->orderBy('id','desc')->paginate(20);
        $data['users']->appends(array_filter($request->all()));
        return view('user.vendor', $data);
    }


    /**
     * [distributorCreate description]供应商新增
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function vendorCreate(Request $request)
    {
        $user =Auth::user();
        $data['paymentmethods'] = PaymentMethod::user($user)->active()->get();
        $data['qualificationMap'] = $user->qualificationMap();
        return view('user.vendor_create', $data);
    }

    /**
     * [vendorCreateSave description] 供应商新增保存
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function vendorCreateSave(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();
        $message = $this->userValidator($data);
        if (is_array($message)) {
            return ['result' => false, 'message'=>$message];
        }
        $data['status'] = $data['status']=='true'? 1: 0;
        $data['role'] = 'vendor';
        $data['parent_id'] = $user->getParentId();
        $data['logo'] = explode(',',$data['logo'])[0];
        $data['password'] = bcrypt($data['password']);
        $vendor = User::create($data);
        $vendor->setUserCode();
        UserAccount::createAccountByUser($vendor);

        $request->request->remove('password');
        $request->request->remove('comfirm_password');
        OperationLog::createFromRequest($request);
        return ['result' => true, 'message' => self::OPERATE_SUCCESS];
    }

    /**
     * [vendorDelete description] 供应商删除操作
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    // public function vendorDelete(Request $request)
    // {
    //     $id = $request->get('id');
    //     $user = Auth::user();
    //     if (!$user = User::whereId($id)->user($user)->vendor()->first()) {
    //         return ['result'=>false ,'message' => self::AUTH_LOWER];
    //     }
    //     $user->delete();
        
    //     // OperationLog::createFromRequest($request);
    //     if ($user->trashed()) {
    //         return response()->json(array('result' => true, 'message' => self::DELETE_SUCCESS));
    //     } else {
    //         return response()->json(array('result' => false, 'message' => self::DELETE_FAIL));
    //     }
    // }

    /**
     * [vendorEdit description]供应商编辑
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function vendorEdit(Request $request)
    {
        $user = Auth::user();
        $id = Hashid::decode($request->id);
        if ( !$id || !$userEdit = User::user($user)->whereId($id)->vendor()->first()  ) {
            return back();
        }
        $data['user'] = $userEdit;
        $data['loginer'] = $user;
        $data['paymentmethods'] = PaymentMethod::user($user)->active()->get();
        $data['qualificationMap'] = $user->qualificationMap();
        return view('user.vendor_edit', $data);
    }

    /**
     * [vendorEditSave description] 供应商编辑保存
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function vendorEditSave(Request $request)
    {
        $data = $request->all();
        $data['id'] = Hashid::decode($data['id']);
        $data = array_filter($data);
        $message = $this->userValidator($data);
        if (is_array($message)) {
            return ['result' => false, 'message'=>$message];
        }

        //验证用户权限
        $user = Auth::user();
        $data['status'] = $data['status']=='true'? 1: 0;
        $effectNumber = User::whereId($data['id'])->user($user)->vendor()->update($data);

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
            unset($data['comfirm_password']);
        }
        OperationLog::createFromRequest($request);
        if ($effectNumber) {
            return ['result' => true, 'message' => self::OPERATE_SUCCESS];
        } else {
            return ['result' => false, 'message' => self::OPERATE_FAIL];
        }
    }

    /**
     * 子账户管理
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function subaccount(Request $request)
    {
        $user =Auth::user();
        $searchs = $request->only('user_code', 'name', 'level', 'credit', 'status');
        $conditions = [
            'user_code' => '=',
            'name' => 'like',
            'level' => '=',
            'credit' => '=',
            'status' => '=',
        ];
        $data['users'] = User::user($user)->search($searchs, $conditions)->subaccount()->orderBy('id')->paginate(20);
        $data['users']->appends(array_filter($request->all()));
        $data['normal_permission'] = config('modelPermission.normal_permission');
        return view('user.subaccount', $data);
    }

    /**
     * 子账户创建页
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function subaccountCreate(Request $request)
    {
        $user =Auth::user();
        $data['userGroups'] = UserGroup::user($user)->get();
        return view('user.subaccount_create', $data);
    }

    /**
     * 子账户创建保存
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function subaccountCreateSave(Request $request)
    {
        $user = Auth::user();
        $userId = $user->getParentId();
        $data = $request->all();
        $message = $this->userValidator($data);
        if (is_array($message)) {
            return ['result' => false, 'message'=>$message];
        }
        $data['status'] = $data['status']=='true'? 1: 0;
        $data['role'] = 'subaccount';
        $data['parent_id'] = $userId;
        $data['logo'] = explode(',',$data['logo'])[0];
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        $user->setUserCode();
        $request->request->remove('password');
        $request->request->remove('comfirm_password');
        OperationLog::createFromRequest($request);
        return ['result' => true, 'message' => self::OPERATE_SUCCESS];
    }

    /**
     * 子账户编辑页
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function subaccountEdit(Request $request)
    {
        $user = Auth::user();
        $id = Hashid::decode($request->id);
        if (!$id || !$userEdit = User::user($user)->whereId($id)->first()) {
            return back();
        }
        $data['user'] = $userEdit;
        $data['userGroups'] = UserGroup::user($user)->get();
        return view('user.subaccount_edit', $data);
    }

    /**
     * 子账户编辑保存
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function subaccountEditSave(Request $request)
    {
        $data = $request->all();
        $data = array_filter($data);
        $message = $this->userValidator($data);
        if (is_array($message)) {
            return ['result' => false, 'message'=>$message];
        }

        //验证用户权限
        $user = Auth::user();
        $data['status'] = $data['status']=='true'? 1: 0;
        if (isset($data['group_id'])) {
            foreach ($data['group_id'] as $key => $groupId) {
                $data['group_id'][$key] = Hashid::decode($groupId);
            }
            $data['group_id'] = implode(',', $data['group_id']);
        }

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
            unset($data['comfirm_password']);
        }
        
        $effectNumber = User::whereId($data['id'])->user($user)->update($data);
        $request->request->remove('password');
        $request->request->remove('comfirm_password');
        OperationLog::createFromRequest($request);
        if ($effectNumber) {
            return ['result' => true, 'message' => self::OPERATE_SUCCESS];
        } else {
            return ['result' => false, 'message' => self::OPERATE_FAIL];
        }
    }

    /**
     * 子账号删除
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function subaccountDelete(Request $request){
        $id = Hashid::decode($request->id);
        $user = Auth::user();
        if (!$user = User::whereId($id)->user($user)->first()) {
            return ['result'=>false ,'message' => self::AUTH_LOWER];
        }
        $user->delete();
        
        OperationLog::createFromRequest($request);
        if ($user->trashed()) {
            return response()->json(array('result' => true, 'message' => self::DELETE_SUCCESS));
        } else {
            return response()->json(array('result' => false, 'message' => self::DELETE_FAIL));
        }
    }

    public function userGroup(Request $request)
    {
        $user =Auth::user();
        $data['user'] = $user;
        $data['normal_permission'] = config('modelPermission.normal_permission.subaccount');
        $data['groups'] = UserGroup::user($user)->get();
        return view('user.group', $data);
    }

    /**
     * 用户组创建保存
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function userGroupCreateSave(Request $request)
    {
        $user = Auth::user();
        $userId = $user->getParentId();
        $data = $request->all();
        $data['user_id'] = $userId;

        $message = $this->userGroupValidator($data);
        if (is_array($message)) {
            return ['result' => false, 'message'=>$message];
        }
        UserGroup::create($data);
        OperationLog::createFromRequest($request);
        return ['result' => true, 'message' => self::OPERATE_SUCCESS];
    }

    public function userGroupValidator($data){
        $validate = 
        [   'name' => 'required|max:255|unique:user_groups,name,'.(isset($data['id']) ? $data['id'] : 'null').',id,user_id,'.$data['user_id'].',deleted_at,null',
        ];
        $message = 
        [
            'required' => self::GROUP_NULL,
            'unique' => self::GROUP_REPEAT,
            'max' => self::CHAR_LONG,
        ];
        $validator = Validator::make($data, $validate, $message);
        if ($validator->fails()) {
            return $validator->errors()->toArray();
        }
        return true;
    }

    /**
     * 用户组权限创建保存
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function userGroupPermissionSave(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();
        $data['id'] = Hashid::decode($data['id']);
        $userGroup = UserGroup::user($user)->whereId($data['id'])->first();
        if (!$userGroup) {
            return ['result' => false, 'message'=> self::AUTH_LOWER];
        }
        $userGroup->permission = $data['permission'];
        $userGroup->save();
        OperationLog::createFromRequest($request);
        return ['result' => true, 'message' => self::OPERATE_SUCCESS];
    }

    public function userGroupEditSave(Request $request)
    {
        $user = Auth::user();
        $userId = $user->getParentId();
        $data = $request->all();
        $data['id'] = Hashid::decode($request->id);
        $data['user_id'] = $userId;

        $message = $this->userGroupValidator($data);
        if (is_array($message)) {
            return ['result' => false, 'message'=>$message];
        }
        if (!$userGroup = UserGroup::whereId($data['id'])->user($user)->first()) {
            return ['result' => false, 'message'=> self::AUTH_LOWER];
        }
        $userGroup->name = $data['name'];
        $userGroup->save();
        OperationLog::createFromRequest($request);
        return ['result' => true, 'message' => self::OPERATE_SUCCESS];
    }

    /**
     * [distributorDownload description]经销商下载
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function distributorDownload(Request $request)
    {
        $user = Auth::user();
        $searchs = $request->only('user_code', 'name', 'level', 'credit', 'status');
        $conditions = [
            'user_code' => '=',
            'name' => 'like',
            'level' => '=',
            'credit' => '=',
            'status' => '=',
        ];
        $data = User::user($user)->search($searchs, $conditions)->distributor()->orderBy('id')->get();
        $downloadService = new DownloadService('111' ,['a','b' ,'c'] , [ 0 => [ 1 , 2 , 3 ] , 1=> [2,3,4]  , 2 => [3,'' , '5']]);
        $data = $downloadService->download();
        //下载成功
        if($data['result']){
            return response()->download($data['url']);
        }
        return $data;
        
    }

    public function userGroupDelete(Request $request)
    {
        $id = Hashid::decode($request->id);
        $user = Auth::user();
        if (!$userGroup = UserGroup::whereId($id)->user($user)->first()) {
            return ['result'=>false ,'message' => self::AUTH_LOWER];
        }
        $userGroup->delete();
        OperationLog::createFromRequest($request);
        if ($userGroup->trashed()) {
            return response()->json(array('result' => true, 'message' => self::DELETE_SUCCESS));
        } else {
            return response()->json(array('result' => false, 'message' => self::DELETE_FAIL));
        }
    }


    /**
     * [distributorUpload description] 上传测试方法
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function distributorUpload(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), ['upload' => 'required|mimes:xls,xlsx'], ['mimes' => self::MIMES_ERROR, 'required' => self::NOT_EMPTY]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        // DB::beginTransaction();
        // try {
            $file = $request->file('upload');
            $newName = time().'-xxUpload.xlsx';
            $path = $file->move(storage_path().'/upload/', $newName);
            $th = [ 'user_error' , 'test' , 'test2' , 'test3' , 'test54' ];
            $uploadService = new UploadService($path , $th);
            $data = $uploadService->getUploadData();
            //可先校验数据准确性后加入MODEL ::create ($data);
            dd($data);
            // DB::commit();
        // } catch (\Exception $e) {
        //     // DB::rollBack();
        //     return redirect('/user/distributor');
        // }

    }

    

    

}
