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
        $log_str=date('Y-m-d H:i:s')."\n".$data."\n<<<<<<";
        file_put_contents('logs/wx_event.log',$log_str,FILE_APPEND);
    }
    /** 获取微信access_token */
    public function getWXAccessToken(){
        //获取缓存
        $token=Redis::get($this->redis_weixin_access_token);
        if($token){
            $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WEIXIN_APPID').'&secret='.env('WEIXIN_APPSECRET');
            $data=json_decode(file_get_contents($url),true);
            //记录缓存
            $token=$data['access_token'];
            Redis::set($this->redis_weixin_access_token,$token);
            Redis::setTimeout($this->redis_weixin_access_token,3600);
        }
        return $token;
    }
    /**
     * 获取用户信息
     */
    public function getUserInfo($openid){
        $openid='wxc28bbb4fa8c63d2b';
        $access_token=$this->getWXAccessToken();
        $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'openid='.$openid.'&lang=zh_CN';
        $data=json_decode(file_get_contents($url),true);
        echo '<pre>';print_r($data);echo'</pre>';
    }
}
