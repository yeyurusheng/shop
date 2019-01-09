<?php

namespace App\Http\Controllers\GOods;

use App\Model\GoodsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsController extends Controller{
    /** 商品展示 */
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
