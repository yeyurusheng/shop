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

Route::get('/adduser','User\UserController@add');

//路由跳转
Route::redirect('/hello1','/world1',301);
Route::get('/world1','Test\TestController@world1');

Route::get('hello2','Test\TestController@hello2');
Route::get('world2','Test\TestController@world2');

Route::get('wei','Test\TestController@wei');
Route::get('ting','Test\TestController@ting');


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

/** 注册 */
Route::get('/register','User\UserController@register');
Route::post('/register','User\UserController@doreg');

/** 登录 */
Route::get('/login','User\UserController@login');
Route::post('/login','User\UserController@dologin');

//mvc
Route::get('mvc/test','Mvc\mvcController@mvc');

//展示
Route::get('show','User\UserController@show');

//cookie
Route::get('cookie','Test\TestController@cookieTest');
Route::get('cookie2','Test\TestController@cookieTest2');