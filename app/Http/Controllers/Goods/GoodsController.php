<?php

namespace App\Http\Controllers\GOods;

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
}


