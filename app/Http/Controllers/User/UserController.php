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
			echo '密码与确认密码不一致';
			//header('refresh:2,/register');exit;
			//header('refresh:2,/meregister');exit;
		}
		$pwd=password_hash($pwd1,PASSWORD_BCRYPT);
		$data=[
			'u_name'=>$request->input('name'),
			'pwd'=>$pwd,
			'age'=>$request->input('age'),
			'email'=>$request->input('email'),
			'time'=>time()
		];
		$uid=userModel::insertGetId($data);
		//var_dump($list);
		//var_dump($uid);
		//if($data['name'])
		$name=$request->input('name');
		$where=[
			'u_name'=>$name
		];
		$res=UserModel::where($where)->first();
		if($res){
			echo '账号已存在';
			//header('refresh:2,/register');
			//header('refresh:2,/meregister');
		}else{
			$list=UserModel::insert($data);
			setcookie('list',$list,time()+86400,'/','login.com',false,true);
			echo '注册成功';
			//header('refresh:2,/melogin');
		}
	}
	/** 用户登录视图 */
	public function login(){
		return view('users.login');
	}
	/** 用户登录 */
	public function doLogin(Request $request){
		$name=$request->input('name');
		$pwd=$request->input('pwd');
		//var_dump($where);
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
		}else{
			//header('refresh:2;/melogin');
			echo '密码错误';
		};
		//print_r($add);
		//echo '<pre>';print_r($_POST);echo '<pre>';
		//print_r($data);
	}
	/** 展示 */
	public function show(Request $request){
		//echo '<pre>';var_dump($request->session());echo '</pre>';
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
