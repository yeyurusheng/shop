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
    public function weixinLogin(){
        // 1 回调拿到 code (用户确认登录后 微信会跳 redirect )
        echo '<pre>';print_r($_GET);echo '</pre>';echo '<hr>';
        echo '<pre>';print_r($_POST);echo '</pre>';

        $code = $_GET['code'];          // code
    }
}
