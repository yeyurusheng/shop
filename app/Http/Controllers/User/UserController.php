<?php
namespace App\Http\Controllers\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\UserModel;
class UserController extends Controller{
    //
	public function user($uid){
		echo $uid;
	}
	/** 用户注册视图 */
	public function register(){
		return view('users.register');
	}
	/** 用户注册 */
	public function doReg(Request $request){
		//echo __METHOD__;
		//echo '<pre>';print_r($_POST);echo '</pre>';die;
		$pwd1=$request->input('pwd');
		$pwd2=$request->input('pwd2');
		if($pwd1!=$pwd2){
            $response = [
                'error' => '40003',
                'msg'   => '密码与确认密码不一致'
            ];
		}
		$pwd=password_hash($pwd1,PASSWORD_BCRYPT);
		$data=[
			'u_name'=>$request->input('u_name'),
			'pwd'=>$pwd,
			'age'=>$request->input('age'),
			'email'=>$request->input('email'),
			'time'=>time()
		];
		$name=$request->input('name');
		$where=[
			'u_name'=>$name
		];
		$res=UserModel::where($where)->first();
		if($res){
            $response = [
                'error' => '40003',
                'msg'   => '账号已存在'
            ];
		}else{
			$list=UserModel::insert($data);
			setcookie('list',$list,time()+86400,'/','melogin.com',false,true);
            $response = [
                'error' => '0',
                'msg'   => 'ok'
            ];
		}
        return $response;
	}
	/** 用户登录视图 */
	public function login(){
		return view('users.login');
	}
	/** 用户登录 */
	public function doLogin(Request $request){
		$name=$request->input('u_name');
		$pwd=$request->input('pwd');
		$add=UserModel::where(['u_name'=>$name])->first();
		if(empty($add)){
			die('账号不存在');
		}
		//var_dump($add);exit;
		if(password_verify($pwd,$add->pwd)){
			$token = substr(md5(time().mt_rand(1,99999)),10,10);
			setcookie('uid',$add->uid,time()+86400,'/','',false,true);
			setcookie('token',$token,time()+86400,'/','',false,true);
            $response = [
                'error' => '0',
                'msg'   => 'ok'
            ];
			$request->session()->put('u_token',$token);
			$request->session()->put('uid',$add->uid);
		}else{
            $response = [
                'error' => '40003',
                'msg'   => 'fail'
            ];
		};
        return $response;
	}
	/** 展示 */
	public function show(Request $request){
		if(empty($_COOKIE['token'])){
			echo '请先登录';
			header('refresh:2;/melogin');
		}
		if($_COOKIE['token']!=$request->session()->get('u_token')){
			die('非法请求');
		}else{
			//echo '登陆成功';
		}
		$request->session()->get('u_token');
		//print_r($session);exit;
		//echo'<pre>';print_r($_COOKIE);echo'</pre>';
		if(empty($_COOKIE['uid'])){
			echo '请先登录';
			//header('refresh:2;/login');
		}else{
			echo 'UID:'.$_COOKIE['uid'].'欢迎回来';
		}
	}
	/** 退出 */
	public function quit(){
		setcookie('uid','',time()-1);
		header('refresh:1,url=/melogin');
	}
}
