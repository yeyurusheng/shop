<?php

namespace App\Http\Controllers\Exam;

use App\Model\ExamLoginModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{
    //安卓可以和iOS同时在线 PC端不能同时在线

    //生成token
    public function getIosToken($u_id){
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
        $input_status = $request->input('status');
        $where = [
            'u_name'=>$u_name
        ];
        $status = ExamLoginModel::all()->keyBy('status')->where($where);
        $data = ExamLoginModel::where($where)->first();
        if($status==1){     //安卓在线
            if(empty($data) || $data->u_pwd!=md5($u_pwd)){
                $response=[
                    'code'=>50001,
                    'msg'=>'账号或密码错误1！'
                ];
                echo json_encode($response);die;
            }
            if($input_status==2){
                $response=[
                    'code'=>40003,
                    'msg'=>'您的账号已在安卓登录不能在PC端登录'
                ];
                echo json_encode($response);die;
            }
            //验证通过，生成token
            $token = $this->getIosToken($data->u_id);
            $u_id = $data->u_id;
            $response=[
                'code'=>0,
                'msg'=>'success',
                'token'=>$token,
                'u_id'=>$u_id
            ];
            echo json_encode($response);
            $up_status = [
                'status' => $input_status
            ];
            ExamLoginModel::where($where)->update($up_status);
            header('refresh:1,url=/goods/list');

        }else if ($status==3){
            if($input_status==2){
                $response=[
                    'code'=>40003,
                    'msg'=>'您的账号已在ios登录不能在PC端登录'
                ];
                echo json_encode($response);die;
            }
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
            $up_status = [
                'status' => '1'
            ];
            ExamLoginModel::where($where)->update($up_status);
        }else if($status == 2){
            $response=[
                'code'=>40003,
                'msg'=>'您的账号已在ios登录不能在PC端登录'
            ];
            echo json_encode($response);die;
        }
        return $response;
    }


    //登录视图
    public function login(){
        return view('exam.login');
    }
}
