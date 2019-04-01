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
}
