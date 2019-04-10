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
    echo date('Y-m-d h:i:s');
    return view('welcome');
});

Route::get('/adduser','Test\TestController@add');

//路由跳转
Route::redirect('/hello1','/world1',301);
Route::get('/world1','Test\TestController@world1');

Route::get('/hello2','Test\TestController@hello2');
Route::get('/world2','Test\TestController@world2');

Route::get('/wei','Test\TestController@wei');
Route::get('/ting','Test\TestController@ting');

//路由参数
Route::get('/user/{uid}','User\UserController@user');
Route::get('/month/{m}/date/{d}','Test\TestController@md');
Route::get('/name/{str?}','Test\TestController@showName');

// View视图路由
Route::view('/mvc','mvc');
Route::view('/error','error',['code'=>403]);


// Query Builder
Route::get('/query/get','Test\TestController@query1');
Route::get('/query/where','Test\TestController@query2');

//view
Route::get('/test/test','Test\TestController@test');
Route::get('/test/child','Test\TestController@child');

//注册
Route::get('/mereg','User\UserController@register');
Route::post('/meregister','User\UserController@doreg');

// 登录
Route::get('/melogin','User\UserController@login');
Route::post('/melogin','User\UserController@dologin');

//退出
Route::get('/quit','User\UserController@quit');
//mvc
Route::get('/mvc/test','Mvc\mvcController@mvc');

//展示
Route::get('/show','User\UserController@show');

//测试
Route::get('/cookie','Test\TestController@cookieTest');   //cookie
Route::get('/cookie2','Test\TestController@cookieTest2');  //cookie
Route::get('/order/pay/show','Test\TestController@test');  //guzzlehttp
Route::get('/php/test','Test\PhpTestController@test');

//购物车 Cart

Route::get('/cart/cart','Cart\CartController@cart')->middleware('check.login'); //购物车展示
Route::get('/cart/add/{g_id}','Cart\CartController@cartAdd');  //购物车添加
Route::get('/cart/del/{g_id}','Cart\CartController@cartDel');  //购物车删除
Route::get('/cart/add2','Cart\CartController@goodsAdd');
Route::post('/cart/add2','Cart\CartController@goodsAdd');   //购物车数据库添加
Route::get('/cart/del2/{g_id}','Cart\CartController@del2');   //购物车数据库删除

//商品展示 Goods
Route::get('/goods/show/{g_id}','Goods\GoodsController@show');  //商品单个展示
Route::get('/goods/list','Goods\GoodsController@goodsList');    //商品列表展示
Route::get('/goods/test','Goods\GoodsController@test1');
Route::get('/goods/test2','Goods\GoodsController@test2');

//订单 Order
Route::get('/order/add','Order\OrderController@add');//订单添加
Route::get('/order/show','Order\OrderController@show'); //订单展示
Route::get('/order/cancel/{order_sn}','Order\OrderController@cancel'); //订单取消
Route::get('/order/detail','Order\OrderController@detailShow'); //订单详情展示

//支付 Pay
Route::get('pay/show/{order_sn}','Pay\PayController@show');  //支付
Route::get('/pay/alipay/test','Pay\AlipayController@test');         //测试
Route::get('/pay/order/{o_id}','Pay\AlipayController@pay');        //订单支付
Route::post('/pay/alipay/notify','Pay\AlipayController@aliNotify');        //支付宝支付 异步通知回调
Route::get('/pay/alipay/returnpay','Pay\AlipayController@aliReturn');        //支付宝支付 同步通知回调
Route::get('/pay/alipay/del','Pay\AlipayController@del');

//用户登录  考试
//Route::get('exam/login','Login\LoginController@login');
//Route::post('exam/login','Login\LoginController@loginTest');
Route::get('redis','Login\LoginController@redis');
Route::get('/update','Login\LoginController@pwd');
Route::post('/update','Login\LoginController@pwdUpdate');


//微信
Route::get('weixin/token','Weixin\WeixinController@refreshToken');              //刷新token
Route::get('weixin/valid','Weixin\WeixinController@validToken');
Route::post('weixin/valid','Weixin\WeixinController@wxEvent');
Route::get('weixin/valid1','Weixin\WeixinController@validToken1');               //接受微信服务器推送事件
Route::post('weixin/valid1','Weixin\WeixinController@validToken');

Route::get('weixin/create_menu','Weixin\WeixinController@createMenu');           //创建菜单

Route::get('weixin/mass','Weixin\WeixinController@massText');                   // 群发

//微信素材
//Route::get('weixin/material/upload','Weixin\WeixinController@upMaterial');      //上传素材
Route::post('/weixin/material','Weixin\WeixinController@materialTest');
Route::get('weixin/form','Weixin\WeixinController@formShow');                   //素材上传表单
Route::post('weixin/test','Weixin\WeixinController@formTest');
Route::get ('weixin/material/list','Weixin\WeixinController@materialList');    //获取永久素材

//微信聊天
Route::get('/weixin/kefu/service/{openid}','Weixin\WeixinController@chatView');     //客服聊天
Route::get('/weixin/chat/get_msg','Weixin\WeixinController@getChatMsg');     //获取用户聊天信息

Route::get('/weixin/kefu/service','Weixin\WeixinController@getKefuChat');     //获取客服聊天信息


//微信支付
Route::get('weixin/pay/test/{order_sn}','Weixin\WeixinPayController@test');       //微信支付测试
Route::post('/weixin/pay/notice','Weixin\WeixinPayController@notice');     //微信支付通知回调
Route::get('weixin/pay/success','Weixin\WeixinPayController@success');       //微信支付测试

//微信登录
Route::get('weixin/login','Weixin\WeixinLoginController@login');            //微信登录页面
Route::get('weixin/getcode','Weixin\WeixinLoginController@getCode');        //获取code

//微信jssdk
Route::get('weixin/jssdk','Weixin\WeixinController@jssdk');             //微信jssdk
//api
Route::post('api','Api\ApiController@api');             //api

//PassController
Route::get('/index','PassPort\PassController@index')->middleware('pass.login');



//exam
Route::get('/exam/login','Exam\LoginController@login');        //登录视图
Route::post('/exam/dologin','Exam\LoginController@dologin');    //登录
Route::get('/exam/token','Exam\LoginController@getToken');      //token
Route::get('/exam/status','Exam\LoginStatusController@status');

//curl   图片上传
Route::post('/upload','Upload\UploadController@upload');

Route::any('vcode/{sid}','Vcode\VcodeController@showCode');  //验证码
Route::any('addcode/{sid}','Vcode\VcodeController@addCode');  //验证码

Route::get('sid','Vcode\VcodeController@sid');  //验证码




Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
