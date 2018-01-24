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

Auth::routes();

Route::post('/alipay/notify_url', 'AccountController@alipayNotifyUrl');
Route::get('/alipay/state', 'AccountController@alipayState');

//未登录重定向与登出重定向
Route::get('/login', function () {
    return redirect('/');
});

Route::post('login', [ 'as' => 'login', 'uses' => 'HomeController@index']);

Route::group(['middleware' => ['web', 'auth', 'permission']], function () {

    Route::get('/home', 'HomeController@index');
    //经销商管理
    Route::get('/user/distributor', 'UserController@distributor');
    Route::get('/user/distributor/create', 'UserController@distributorCreate');
    Route::post('/user/distributor/create', 'UserController@distributorCreatesave');
    Route::get('/user/distributor/edit', 'UserController@distributorEdit');
    Route::post('/user/distributor/edit', 'UserController@distributorEditSave');
    Route::get('/user/distributor/download', 'UserController@distributorDownload');

    Route::any('/user/distributor/upload', 'UserController@distributorUpload');
    

    


    //供应商管理
    Route::get('/user/vendor', 'UserController@vendor');
    Route::get('/user/vendor/create', 'UserController@vendorCreate');
    Route::post('/user/vendor/create', 'UserController@vendorCreatesave');
    // Route::post('/user/vendor/delete', 'UserController@vendorDelete');
    
    Route::get('/user/vendor/edit', 'UserController@vendorEdit');
    Route::post('/user/vendor/edit', 'UserController@vendorEditSave');

    Route::post('/user/logo', 'UserController@uploadUserLogo');
    Route::get('/user/logo', 'UserController@getUserLogo');


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

    //交易详情
    Route::get('/user/transaction/log', 'AccountController@userTransactionLog');
    Route::get('/distributor/transaction/log', 'AccountController@distributorTransactionLog');
    Route::get('/vendor/transaction/log', 'AccountController@vendorTransactionLog');
    //支付方式配置
    Route::get('/account/paymentmethod', 'AccountController@paymentMethod');
    Route::get('/account/paymentmethod/create', 'AccountController@paymentMethodCreate');
    Route::post('/account/paymentmethod/create', 'AccountController@paymentMethodCreateSave');
    Route::get('/account/paymentmethod/save', 'AccountController@paymentMethodSave');
    Route::post('/account/paymentmethod/save', 'AccountController@paymentMethodSaveCreate');
    Route::post('/account/paymentmethod/status/save', 'AccountController@paymentMethodStatusSave');
    Route::get('/get/paymentmethod', 'AccountController@getPaymentMethod');

    //账户列表
    Route::get('/account/user', 'AccountController@accountUser');
    Route::get('/account/amount/edit', 'AccountController@amountEdit');
    Route::get('/account/distributor', 'AccountController@distributor');
    Route::get('/account/vendor', 'AccountController@vendor');
    Route::get('/account/transaction/onlinepay', 'AccountController@onlinePay');
    //充值
    Route::post('/user/recharge', 'AccountController@recharge');

    //商品管理
    //供应商
    Route::get('/goods/basic', 'GoodsController@goodsbasic');
    Route::get('/goods/basic/create', 'GoodsController@goodsbasicCreate');
    Route::post('/goods/basic/create', 'GoodsController@goodsbasicCreateSave');
    Route::get('/goods/images', 'GoodsController@getGoodsImage');
    Route::post('/goods/images', 'UserController@uploadUserLogo');
    Route::get('/goods/basic/edit', 'GoodsController@goodsbasicEdit');
    Route::post('/goods/basic/edit', 'GoodsController@goodsbasicEditSave');
    Route::post('/goods/basic/push', 'GoodsController@goodsbasicPush');
    //管理员
    Route::get('/goods/online', 'GoodsController@goodsonline');
    Route::get('/goods/online/edit', 'GoodsController@goodsonlineEdit');
    Route::post('/goods/online/edit', 'GoodsController@goodsonlineEditSave');
    Route::get('/goods/online/images', 'GoodsController@getGoodsOnlineImage');
    Route::post('/goods/online/images', 'UserController@uploadUserLogo');
    Route::post('/goods/online/status', 'GoodsController@goodsOnlineStatus');
    Route::get('/goods/category', 'GoodsController@goodsCategory');
    Route::get('/goods/category/create', 'GoodsController@goodsCategoryCreate');
    Route::post('/goods/category/create', 'GoodsController@goodsCategoryCreateSave');
    Route::get('/goods/category/edit', 'GoodsController@goodsCategoryEdit');
    Route::post('/goods/category/edit', 'GoodsController@goodsCategoryEditSave');

    

    //系统管理节点系统管理
    Route::get('/system/manage', 'SystemController@manage');
    //获取验证码
    Route::get('/system/verify-code', 'SystemController@getVerifyCode');
    Route::get('/system/change-phone', 'SystemController@changePhone');
    Route::get('/system/change-payment-password', 'SystemController@changePaymentPassword');


});

