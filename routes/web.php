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


Route::get('/article/view/{id}', 'ArticleController@view');





Auth::routes();

//未登录重定向与登出重定向
Route::get('/login', function () {
    return redirect('/');
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

    Route::post('/article/status', 'ArticleController@statusChange');
    
    Route::post('/article/delete', 'ArticleController@delete');
    

    //文章分类管理
    Route::get('/article/category', 'ArticleController@category');
    Route::get('/article/category/create', 'ArticleController@categoryCreate');
    Route::post('/article/category/create', 'ArticleController@categoryCreateSave');
    Route::get('/article/category/edit', 'ArticleController@categoryEdit');
    Route::post('/article/category/edit', 'ArticleController@categoryEditSave');

    

});

