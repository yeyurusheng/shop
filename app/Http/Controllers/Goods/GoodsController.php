<?php

namespace App\Http\Controllers\GOods;

use App\Model\ExamLoginModel;
use App\Model\GoodsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GoodsController extends Controller{
    public function __construct(){
        $this->middleware('auth');
    }

    /** 商品列表展示 */
    public function goodsList(){
        $goods=GoodsModel::all();
        //var_dump($goods);exit;
        $data=[
            'list'=>$goods
        ];
        //var_dump($data);exit;
        $status = ExamLoginModel::where(['uid'==1])->first();
        if(!empty($status)){
            echo '账号已在APP端登录';exit;
        }
        return view('goods.goods',$data);

    }

    /** 商品单个展示 */
    public function show($g_id){
        $goods=GoodsModel::where(['g_id'=>$g_id])->first();
        //var_dump($goods);
        if(!$goods){
            echo '商品不存在';
            header('refresh:1,url=/show');
        }
        $data=[
            'goods'=>$goods
        ];
        return view('goods.show',$data);

    }

    public function test1()
    {
        $arr=[1,2,3,4,5,6,7];
        $new = [];
        $j = 0;
        for ($i = 0; $i < count($arr); $i++) {
            if ($i % 2 == 1) {
                $new[$j][] = $arr[$i];$j++;
            } else {
                $new[$j][] = $arr[$i];
            }

        }
        print_r($new);
    }
   public function test2(){
       $arr[0] = $arr[1] = 1;
       $n = 10;
       for($i=2;$i<$n;$i++){
           $arr[$i] = $arr[$i-1]+$arr[$i-2];
       }
       print_r($arr);
   }
}


