<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App\ArticleCategory;
use App\Article;
use App\Banner;
use App\Services\Qiniu;

use App\Services\Helper\Helper;


class ArticleController extends Controller
{

	const REQUIRED = '*为必填项';
	const OPERATE_SUCCESS = '操作成功';
	const CATEGORYREPLACE = '分类名称重复';
    const DATA_ERROR = '数据异常';


    /**
     * [index description]文件界面入口
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $data['articles'] = Article::paginate(20);
        return view('article.index' , $data);
    }

    //Logo上传
    public function uploadArticleImage(Request $request)
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
    public function getArticleImage(Request $request)
    {
        $user = Auth::user();
        $id = $request->id;
        if (!$id) {
            return [];
        }
        $logo = Article::whereId($id)->pluck('image')->first();
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
     * [create description]文章创建
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $data['categories'] = ArticleCategory::get();
        $data['article'] = Article::whereId($request->get('id'))->first();
        return view('article.create' , $data);
    }

    /**
     * [statusChange description]修改文章状态
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function statusChange(Request $request)
    {
        $id = $request->get('id');
        $status = $request->get('status');
        $article = Article::whereId($id)->first();
        if(!$article){
            return ['result' => false ,'message' => self::DATA_ERROR];
        }
        $article->update(['status' => $status]);
        return ['result' => true ,'message' => self::OPERATE_SUCCESS];
    }

    /**
     * [delete description]删除
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function delete(Request $request)
    {
        $id = $request->get('id');
        
        $article = Article::whereId($id)->first();
        if(!$article){
            return ['result' => false ,'message' => self::DATA_ERROR];
        }
        $article->delete();
        return ['result' => true ,'message' => self::OPERATE_SUCCESS];
    }

    /**
     * [categoryDelete description]分类删除
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function categoryDelete(Request $request)
    {
        $id = $request->get('id');
        
        $banner = ArticleCategory::whereId($id)->first();
        if(!$banner){
            return ['result' => false ,'message' => self::DATA_ERROR];
        }
        $banner->delete();
        return ['result' => true ,'message' => self::OPERATE_SUCCESS];
    }

    /**
     * [bannerDelete description]删除Banner
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function bannerDelete(Request $request)
    {
        $id = $request->get('id');
        
        $banner = Banner::whereId($id)->first();
        if(!$banner){
            return ['result' => false ,'message' => self::DATA_ERROR];
        }
        $banner->delete();
        return ['result' => true ,'message' => self::OPERATE_SUCCESS];
    }

    /**
     * [view description]文章浏览
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function view($id)
    {
        $article = Article::whereId($id)->where('status' ,'publish')->first();
        if(!$article){
            abort(404);
        }
        $data['article'] = $article;
        return view('article.view', $data);
    }

    /**
     * [home description]Home 主页面
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function home(Request $request)
    {
        $data['banners'] = Banner::orderBy('index')->get();
        $data['categories'] = ArticleCategory::get();
        $data['isMobile'] = Helper::isMobileClient();
        return view('article.homePc', $data);
    }


    /**
     * [updateSave description]文章保存
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function createSave(Request $request){
        $user = Auth::user();
        $data = $request->only('id', 'title', 'content' ,'article_category_id','image');
        $data['author'] = $user->name;
        //创建
        if (!isset($data['id'])) {
            $article = Article::create($data);
            return ['result' => true ,'id' => $article->id ,'message' => self::OPERATE_SUCCESS];
        } else{
            $article = Article::whereId($data['id'])->first();
            if ($article) {
                $article->update($data);
                return ['result' => true ,'id' => $article->id ,'message' => self::OPERATE_SUCCESS];
            }
        }
    }

	/**
	 * [category description]文章分类入口
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
    public function category(Request $request)
    {
    	$user = Auth::user();
    	$data['categories'] = ArticleCategory::paginate(20);
    	$data['colorMap'] = ArticleCategory::COLOR_MAP;
    	return view('article.category' , $data);
    }


    public function categoryEdit(Request $request)
    {
        $user = Auth::user();
        
        $id = $request->id;
        $data['category'] = ArticleCategory::whereId($id)->first();
        if (!$data['category']) {
            return back();
        }
        $data['colorMap'] = ArticleCategory::COLOR_MAP;
        return view('article.category_edit', $data);
    }

    public function categoryEditSave(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();
        $data['id'] = $request->id;
        $message = $this->articleCategoryValidator($data);
        if (is_array($message)) {
            return ['result' => false, 'message'=> $message];
        }
        $category = ArticleCategory::whereId($data['id'])->first();
        if (!$category) {
            return back();
        }
        $category->category_name = $request->category_name;
        $category->content_color = $request->content_color;
        $category->save();
        return ['result' => true, 'message' => self::OPERATE_SUCCESS];
    }


    /**
     * [categoryCreate description]文章分类
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function categoryCreate(Request $request)
    {	
    	$user = Auth::user();
    	$data['colorMap'] = ArticleCategory::COLOR_MAP;
    	return view('article.category_create' , $data);
    }

    /**
     * [bannerIndex description]Banner管理
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function bannerIndex(Request $request)
    {
        $user = Auth::user();
        $data['banners'] = Banner::orderBy('index')->get();
        return view('article.banner_index' , $data);
    }

    /**
     * [getBannerImage description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getBannerImage(Request $request)
    {
        $user = Auth::user();
        $id = $request->id;
        if (!$id) {
            return [];
        }
        $logo = Banner::whereId($id)->pluck('images')->first();
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
     * [uploadImage description]上传图片
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function uploadImage(Request $request)
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

    /**
     * [create description]文章创建
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function bannerCreate(Request $request)
    {
        $user = Auth::user();
        $data['banner'] = Banner::whereId($request->get('id'))->first();
        return view('article.banner_create' , $data);
    }


    /** 
     * [bannerCreate description]创建banner
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function bannerCreateSave(Request $request)
    {
        $user = Auth::user();
        $id = $request->get('id');
        $fields = ['link_url' => $request->get('link_url') ,'title' => $request->get('title') ,'images' => $request->get('images') ,'index' => $request->get('index')];
        if($id){
            $conds = ['id' => $id];
            Banner::updateOrCreate($conds, $fields);
        }else{
            Banner::create($fields);
        }
        
        return ['result' => true ,'message' => '操作成功'];
    }


    /**
     * [categoryCreateSave description]文章分类保存
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function categoryCreateSave(Request $request)
    {
    	$user = Auth::user();
    	
    	$data = $request->all();
    	$message = $this->articleCategoryValidator($data);
    	if (is_array($message)) {
    	    return ['result' => false, 'message'=> $message];
    	}
    	ArticleCategory::create($data);
    	return ['result' => true, 'message' => self::OPERATE_SUCCESS];
    }

    public function articleCategoryValidator($data){
        $validate = 
        [
            'category_name' => 'required|max:255|unique:article_categories,category_name,'.(isset($data['id']) ? $data['id'] : 'null').',id',
            'content_color' => 'required',
        ];
        $message = 
        [
            'required' => self::REQUIRED,
            'unique' => self::CATEGORYREPLACE,
        ];
        $validator = Validator::make($data, $validate, $message);
        if ($validator->fails()) {
            return $validator->errors()->toArray();
        }
        return true;
    }



}
