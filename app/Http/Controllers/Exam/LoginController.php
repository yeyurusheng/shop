<?php

namespace App\Http\Controllers\Exam;

use App\Model\ExamLoginModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{
    //生成token
    public function getToken($u_id){
        $str = time().$u_id.mt_rand(100000000000,999999999999);
        $str = md5($str);
        $token=substr($str,1,20);
        $redis_token="redis_token_str:".$u_id;
        Redis::hset($redis_token,'utoken',$token);
        Redis::expire($redis_token,120);  //设置过期时间
        return $token;
    }
    //生成apptoken
    public function getAppToken($u_id){
        $str = time().$u_id.mt_rand(100000000000,999999999999);
        $str = md5($str);
        $token=substr($str,1,20);
        $redis_token="redis_token_str:".$u_id;
        Redis::hset($redis_token,'apptoken',$token);
        Redis::expire($redis_token,120);  //设置过期时间
        return $token;
    }
    //登录
    public function dologin(Request $request){
        $u_name = $request->input('u_name');
        $u_pwd = $request->input('u_pwd');
        $app = $request->input('app');
        var_dump($app);
        $where = [
            'u_name'=>$u_name
        ];
        $data = ExamLoginModel::where($where)->first();
        if(empty($app)){
            if(empty($data) || $data->u_pwd!=md5($u_pwd)){
                $response=[
                    'code'=>50001,
                    'msg'=>'账号或密码错误1！'
                ];
                echo json_encode($response);die;
            }
            //验证通过，生成token
            $token = $this->getToken($data->u_id);
            $u_id = $data->u_id;
            $response=[
                'code'=>0,
                'msg'=>'success',
                'token'=>$token,
                'u_id'=>$u_id
            ];
            echo json_encode($response);
        }else{
            if(empty($data) || $data->u_pwd!=md5($u_pwd)){
                $response=[
                    'code'=>50001,
                    'msg'=>'账号或密码错误1！'
                ];
                echo json_encode($response);die;
            }
            //验证通过，生成token
            $token = $this->getAppToken($data->u_id);
            $u_id = $data->u_id;
            $response=[
                'code'=>0,
                'msg'=>'success',
                'token'=>$token,
                'u_id'=>$u_id
            ];
            echo json_encode($response);
        }

    }

    //登录视图
    public function login(){
        return view('exam.login');
    }
}
