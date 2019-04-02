<?php
namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;


class ApiPwdController extends Controller
{
    public function test(){
        $str="hello word";
        $key="pass";
        $iv=mt_rand(1111,9999)."aaaabbbbcccc";
        //加密
        $enc_str=openssl_encrypt($str,'AES-128-CBC',$key,OPENSSL_RAW_DATA,$iv);
        $str=base64_encode($enc_str);
        var_dump($enc_str);echo '</br>';
        var_dump($str);echo '</br>';
        //解密
        $dec_str=openssl_decrypt($enc_str,"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv);
        var_dump($dec_str);

    }
    //响应请求
    public function tes(){
        //接收请求
        $timestamp=$_GET['t'];
        $key="pass";
        $salt="****";
        $method="AES-128-CBC";
        $iv=substr(md5($timestamp.$salt),5,16);

        //签名
        $sign=base64_decode($_POST["sign"]);
        //var_dump($sign);
        $base64_data=$_POST['data'];
        //var_dump($base64_data);die;
        //验证签名
        $pub_res=openssl_pkey_get_public(file_get_contents("/home/wwwroot/laravel_api/public/test/public.pem"));
        //var_dump($pub_res);
        $pub_sign=openssl_verify($base64_data,$sign,$pub_res,OPENSSL_ALGO_SHA256);
        var_dump($pub_sign);
        if(!$pub_sign){
            echo "fail";
        }
        //接收加密数据
        $post_data=base64_decode($_POST['data']);
        //var_dump($post_data);
        //解密
        $dec_str=openssl_decrypt($post_data,$method,$key,OPENSSL_RAW_DATA,$iv);
        //var_dump($dec_str);
        //解密之后响应给客户端
        if(1){
            $now_time =time();
            $response=[
                "error"=>0,
                "msk"=>"ok",
                "data"=>"test"
            ];
            $iv2=substr(md5($now_time.$salt),5,16);
            $dec_data=json_encode($response);
            //加密响应数据
            $de_str=openssl_encrypt($dec_data,$method,$key,OPENSSL_RAW_DATA,$iv2);
            //var_dump($de_str);
            $arr=[
                "t"=>$now_time,
                "data"=>base64_encode($de_str),
            ];
            //echo json_encode($arr);

        }

    }

}
