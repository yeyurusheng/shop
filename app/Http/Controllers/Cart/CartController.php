<?php

namespace App\Http\Controllers\Cart;

use App\Model\CartModel;
use App\Model\GoodsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller{
    public function _construct(){

    }
    /** 购物车 */
    public function cart(){
        $goods=session()->get('cart');
        //var_dump( $goods);
        if(empty($goods)){
            echo '购物车为空';
        }else{
            foreach ($goods as $k=>$v){
                echo 'GOODS ID:'.$v;echo'<br>';
                $detail=GoodsModel::where(['g_id'=>$v])->first()->toArray();
                echo '<pre>';print_r($detail);echo '</pre>';
            }
        }
    }
    /** 购物车添加 */
    public function cartAdd($g_id){
        $where=[
            'g_id'=>$g_id
        ];
        //查询有多少库存 如果库存为0则提示
        //查询有多少库存
        $store=GoodsModel::where($where)->value('g_store');
        //echo $store;
        if($store<=0){
            echo '此商品已售空';exit;
        }
        //验证此商品是否添加到购物车
        $cart=session()->get('cart');
        if(!empty($cart)){
            if(in_array($g_id,$cart)){
                echo '此商品已在购物车';exit;
            }
        }
        session()->push('cart',$g_id);
        //减库存
        $res=GoodsModel::where($where)->decrement('g_store');
        if($res){
            echo '添加成功';
        }
    }
    /** 购物车删除 */
    public function cartDel($g_id){
        //判断商品是否在购物车中
        $goods=session()->get('cart');
        echo '<pre>';print_r($goods);echo '</pre>';
        if(in_array($g_id,$goods)){
            //执行删除 session
            foreach($goods as $k=>$v){
                //echo $v.'<br>';
                //echo $k.'<br>';
                if($g_id==$v){
                    session()->pull('cart.'.$k);
                }
            }
        }else{
            echo '此商品不在您的购物车中';
        }
    }
}
