<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/home');
});



Route::post('/front-login', 'Auth\HomeController@login');

Route::get('/article/home/view', 'ArticleController@articleView');

Route::get('/article/view/{id}', 'ArticleController@view');
Route::get('/article/home', 'ArticleController@home');

Auth::routes();

//未登录重定向与登出重定向
Route::get('/login', function () {
    return redirect('/article/home');
});

Route::post('login', [ 'as' => 'login', 'uses' => 'HomeController@index']);

Route::group(['middleware' => ['web', 'auth', 'permission']], function () {

    Route::get('/home', 'HomeController@index');
    //子账户管理
    Route::get('/user/subaccount', 'UserController@subaccount');
    Route::get('/user/subaccount/create', 'UserController@subaccountCreate');
    Route::post('/user/subaccount/create', 'UserController@subaccountCreateSave');
    Route::get('/user/subaccount/edit', 'UserController@subaccountEdit');
    Route::post('/user/subaccount/edit', 'UserController@subaccountEditSave');
    Route::post('/user/subaccount/delete', 'UserController@subaccountDelete');

    //学生管理
    Route::get('/user/student', 'UserController@student');

    Route::get('/user/student/create', 'UserController@studentCreate');
    Route::post('/user/student/create', 'UserController@studentCreatesave');
    Route::post('/user/student/delete', 'UserController@studentDelete');
    
    Route::get('/user/student/edit', 'UserController@studentEdit');
    Route::post('/user/student/edit', 'UserController@studentEditSave');
    
    
    //学生测试管理
    Route::get('/test/holand/index', 'TestController@holandIndex');
    Route::post('/test/holand/submit', 'TestController@holandSubmit');
    Route::get('/test/holand/report', 'TestController@holandReport');
    Route::get('/test/holand/report/detail', 'TestController@holandReportDetail');

    //IMTI测试
    Route::get('/test/mbti/index', 'TestController@mbtiIndex');

    Route::post('/test/mbti/submit', 'TestController@mbtiSubmit');
    Route::get('/test/mbti/report', 'TestController@mbtiReport');

    Route::get('/test/mbti/report/detail', 'TestController@mbtiReportDetail');

    

    //教师账号管理
    Route::get('/test/holand/admin', 'TestController@holandAdmin');
    Route::get('/test/holand/admin/detail', 'TestController@holandAdminDetail');
    

    Route::get('/test/mbti/admin', 'TestController@mbtiAdmin');
    Route::get('/test/mbti/admin/detail', 'TestController@mbtiAdminDetail');


    //用户组管理
    Route::get('/user/group', 'UserController@userGroup');
    Route::post('/user/group/create', 'UserController@userGroupCreateSave');
    Route::post('/user/group/permission/save', 'UserController@userGroupPermissionSave');
    Route::post('/user/group/edit', 'UserController@userGroupEditSave');
    Route::post('/user/group/delete', 'UserController@userGroupDelete');

    //文章管理
    Route::get('/article/index', 'ArticleController@index');
    Route::get('/article/create', 'ArticleController@create');
    Route::post('/article/create', 'ArticleController@createSave');
    Route::get('/article/edit', 'ArticleController@edit');
    Route::post('/article/edit', 'ArticleController@createSave');

    Route::post('/article/images', 'ArticleController@uploadArticleImage');
    Route::get('/article/images', 'ArticleController@getArticleImage');

    

    Route::post('/article/status', 'ArticleController@statusChange');
    
    Route::post('/article/delete', 'ArticleController@delete');

    Route::get('/banner/index', 'ArticleController@bannerIndex');
    Route::get('/banner/images', 'ArticleController@getBannerImage');

    Route::post('/banner/images', 'ArticleController@uploadImage');

    Route::post('/banner/create', 'ArticleController@bannerCreateSave');
    Route::get('/banner/create', 'ArticleController@bannerCreate');
    Route::post('/banner/delete', 'ArticleController@bannerDelete');


    //文章分类管理
    Route::get('/article/category', 'ArticleController@category');
    Route::get('/article/category/create', 'ArticleController@categoryCreate');
    Route::post('/article/category/create', 'ArticleController@categoryCreateSave');
    Route::get('/article/category/edit', 'ArticleController@categoryEdit');
    Route::post('/article/category/edit', 'ArticleController@categoryEditSave');

    Route::post('/category/delete', 'ArticleController@categoryDelete');
    

});

