<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    $router->resource('/goods',GoodsController::class);
    $router->resource('/wx_users',WeixinController::class);     //微信用户
    $router->resource('/wx_media',WeixinMediaController::class);  //微信素材
    $router->get('/group','WeixinGroup@index');    //微信群发
    $router->post('/group','WeixinGroup@groupText');    //微信群发
});












