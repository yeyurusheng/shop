<?php

namespace App\Http\Controllers\Weixin;

use App\Model\UserModel;
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
        $code = $_GET['code'];
        //2 用code换取access_token 请求接口

        $token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxe24f70961302b5a5&secret=0f121743ff20a3a454e4a12aeecef4be&code='.$code.'&grant_type=authorization_code';
        $token_json = file_get_contents($token_url);
        $token_arr = json_decode($token_json,true);
        echo '<hr>';
        echo '<pre>';print_r($token_arr);echo '</pre>';

        $access_token = $token_arr['access_token'];
        $openid = $token_arr['openid'];

        // 3 携带token  获取用户信息
        $user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $user_json = file_get_contents($user_info_url);

        $user_arr = json_decode($user_json,true);
        echo '<hr>';
        echo '<pre>';print_r($user_arr);echo '</pre>';
        $u_name = $user_arr['unionid'];
        echo $u_name;
        $where = ['u_name'=>$u_name];
        $data = UserModel::where($where)->first();
        if($data===null){
            UserModel::insertGetId($where);
            echo '欢迎来到商城';
        }else{
            $add=UserModel::where($where)->first();
            //var_dump($add);exit;
            if(empty($add)){
                die('账号不存在');
            }
            //var_dump($add);exit;
            $token = substr(md5(time() . mt_rand(1, 99999)), 10, 10);
            setcookie('uid', $add->uid, time() + 86400, '/', '', false, true);
            setcookie('token', $token, time() + 86400, '/', '', false, true);
            //echo'<pre>';print_r($_COOKIE);echo'</pre>';
            echo '欢迎回来';
            
        }
    }


}
