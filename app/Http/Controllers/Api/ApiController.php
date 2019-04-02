<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class ApiController extends Controller
{
    public function api(Request $request){
        $u_name = $request->input('u_name');
        $pwd = $request->input('pwd');
        $url = "http://pass.tactshan.com/dologin";    //login
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,['u_name'=>$u_name,'pwd'=>$pwd]);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        $rs = curl_exec($ch);    //接收服务端响应
        $response = json_decode($rs,true);
        if($u_name!=''){
            $token = $response['token'];
            $redis_key = 'token:login:app:'.$u_name;
            Redis::set($redis_key,$token);
        }
        return $response;

    }

    public function quit(){
        setcookie('uid','',time()-1,'/','tactshan.com',false,true);
        $a = setcookie('token','',time()-1,'/','tactshan.com',false,true);
        if($a){
            $response = [
                'error' => 0,
                'msg'   => 'quit success'
            ];
        }
        $response = json_encode($response);
        return $response;
    }

    //接口防刷
    public function order(){
        $request_uri=$_SERVER['REQUEST_URI'];
        //print_r($_SERVER);die;
        $str_hash=substr(md5($request_uri),10,20);
        //echo $str_hash;
        $ip=$_SERVER['SERVER_ADDR'];  //得到客户端ip
        $redis_key='str:'.$str_hash.$ip;
        echo $redis_key;
        $num=Redis::incr($redis_key);   //每访问一次自动+1
        var_dump($num);
        if($num>5){
            //非法请求
            $response=[
                'error'=>40003,
                'msg'=>'invalid!!!'
            ];
            Redis::expire($redis_key,60);  //设置过期时间
            //记录非法请求ip
            $invalid_ip='h:invalid:ip';
            Redis::sAdd($invalid_ip,$ip); //存列表
        }else{
            $response=[
                "msg"=>"success"
            ];
        }
        return $response;

    }

}
