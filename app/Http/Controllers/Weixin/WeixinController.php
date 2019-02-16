<?php

namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WeixinController extends Controller{
    protected $redis_weixin_access_token = 'str:weixin_access_token';

    public function test()
    {
        //echo __METHOD__;
        //$this->getWXAccessToken();
        $this->getUserInfo(1);
    }

    /** 微信首次接入 */
    public function validToken(){
        echo $_GET['echostr'];
    }

    /** 接收微信服务器事件推送 */
    public function wxEvent(){
        $data=file_get_contents("php://input");
        $log_str=date('Y-m-d H:i:s')."\n"."\n<<<<<<";
        file_put_contents('logs/wx_event.log',$log_str,FILE_APPEND);
    }
}
