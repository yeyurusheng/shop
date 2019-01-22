<?php

namespace App\Http\Controllers\Test;

use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use GuzzleHttp\Client;


class TestController extends Controller
{
    /** 请求订单服务 处理订单逻辑 */
	public function test(){
		$url='http://order.lening.com';
		$client=new Client([
			'base_uri'=>$url,
			'timeout'=>2.0,
		]);
		$response=$client->request('CET','/order.php');
	}

	public function world1()
	{
		echo __METHOD__;
	}

	public function wei(){
		echo __METHOD__;
		header('location:/ting');
	}

	public function ting(){
		echo __METHOD__;
	}

	public function hello2()
	{
		echo __METHOD__;
		header('Location:/world2');
	}

	public function world2()
	{
		echo __METHOD__;
	}

	public function md($m,$d)
	{
		echo 'm: '.$m;echo '<br>';
		echo 'd: '.$d;echo '<br>';
	}

	public function showName($name=null)
	{
		var_dump($name);
	}

	public function query1()
	{
		$list = DB::table('p_users')->get()->toArray();
		echo '<pre>';print_r($list);echo '</pre>';
	}

	public function query2()
	{
		$user = DB::table('p_users')->where('uid', 3)->first();
		echo '<pre>';print_r($user);echo '</pre>';echo '<hr>';
		$email = DB::table('p_users')->where('uid', 4)->value('email');
		var_dump($email);echo '<hr>';
		$info = DB::table('p_users')->pluck('age', 'name')->toArray();
		echo '<pre>';print_r($info);echo '</pre>';
	}
	public function test1(){
		$data=[];
		return view('test.index',$data);
	}
	public function child(){
		$list=UserModel::all()->toArray();
		$data=[
			'list'=>$list,
			'title'=>'ting'
		];
		return view('test.child',$data);
	}
	public function cookieTest(){
		setcookie('wei','123',time()+1200,'/','lening.com','false','true');
		echo '<pre>';print_r($_COOKIE);echo'</pre>';
	}
	public function cookieTest2(){
		echo '<pre>';print_r($_COOKIE);echo '</pre>';
	}
	public function add(){
		$data = [
				'name'      => str_random(5),
				'age'       => mt_rand(20,99),
				'email'     => str_random(6) . '@gmail.com',
				'time'  => time()
		];

		$id = UserModel::insertGetId($data);
		var_dump($id);
	}
	public function wang(){
	    echo  date('ymd H:i:s');
    }
}
