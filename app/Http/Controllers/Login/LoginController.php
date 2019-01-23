<?php

namespace App\Http\Controllers\Login;

use App\Model\UserModel;
use Doctrine\Common\Cache\Cache;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{
    /** 用户登录视图 */
    public function login(){
        return view('login.login');
    }
    /**用户登录 */
    public function loginTest(Request $request){
        $name=$request->input('name');
        $pwd=$request->input('pwd');
        $add=UserModel::where(['u_name'=>$name])->first();
        //var_dump($add);exit;
        if(empty($add)){
            die('账号不存在');
        }
        //var_dump($add);exit;
        if(password_verify($pwd,$add->pwd)){
            $token = substr(md5(time().mt_rand(1,99999)),10,10);
            setcookie('uid',$add->uid,time()+86400,'/','',false,true);
            setcookie('token',$token,time()+86400,'/','',false,true);
            //echo'<pre>';print_r($_COOKIE);echo'</pre>';
            echo '登陆成功';
            $request->session()->put('u_token',$token);
            $request->session()->put('uid',$add->uid);
            //header('refresh:2;/show');
            $login=[
                'name'=>$name,
                'pwd'=>$pwd
            ];
            Redis::set('name',$login,30);
            $values = Redis::get('name');
            var_dump($values);
        }else{
            header('refresh:2;/login');
            die('密码错误');
        };
    }

}
