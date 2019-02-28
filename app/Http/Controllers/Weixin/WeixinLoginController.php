<?php

namespace App\Http\Controllers\Weixin;

use App\Model\WeixinMedia;
use App\Model\WeixinService;
use App\Model\WeixinUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class WeixinLoginController extends Controller{
    /**
     * 登录界面
     */
    public function login(){

        return view('weixin.login');
    }

    /**
     * 获取code
     */
    public function getCode(){
        $_GET['code'];
    }

    /**
     * 微信登录
     */
}
