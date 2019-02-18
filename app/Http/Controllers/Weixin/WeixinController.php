<?php

namespace App\Http\Controllers\Weixin;

use App\Model\WeixinUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redis;

class WeixinController extends Controller{
    protected $redis_weixin_access_token = 'str:weixin_access_tokenwei';

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

    /**
     * 接收微信服务器事件推送
     */
    public function wxEvent(){
        $data = file_get_contents("php://input");
        //解析XML
        $xml = simplexml_load_string($data);        //将 xml字符串 转换成对象
        //var_dump($xml);exit;
        $event = $xml->Event;               //事件类型

        if($event=='subscribe') {
            $openid = $xml->FromUserName;               //用户openid
            $sub_time = $xml->CreateTiem;               //扫码关注时间


            echo 'openid:' . $openid;
            echo '</br>';
            echo 'sub_time:' . $sub_time;
            //获取用户信息
            $user_info = $this->getUserInfo($openid);
            echo '<pre>';
            print_r($user_info);
            echo '</pre>';
            //保存用户信息
            $u = WeixinUser::where(['openid' => $openid])->first();
            if ($u) {
                echo '用户不存在';
            } else {
                $user_data = [
                    'openid' => $openid,
                    'add_time' => time(),
                    'nickname' => $user_info['nickname'],
                    'sex' => $user_info['sex'],
                    'headimgurl' => $user_info['headimgurl'],
                    'subscribe_time' => $sub_time,
                ];
                var_dump($user_data);exit;
                $id = WeixinUser::insertGetId($user_data);    //保存用户信息
                //var_dump($id);
            }
        }
        $log_str=date('Y-m-d H:i:s')."\n".$data."\n<<<<<<";
        file_put_contents('logs/wx_event.log',$log_str,FILE_APPEND);
    }

    /**
     * 获取微信access_token
     * @return mixed
     */
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
