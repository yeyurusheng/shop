<?php

namespace App\Http\Controllers\Cart;

use App\Model\CartModel;
use App\Model\GoodsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller{
    public function _construct(){

    }
    /** 购物车展示 */
    public function cart(){
        /*$goods=session()->get('cart');
        //var_dump( $goods);
        if(empty($goods)){
            echo '购物车为空';
        }else{
            foreach ($goods as $k=>$v){
                //echo 'GOODS ID:'.$v;echo'<br>';
                $detail=GoodsModel::where(['g_id'=>$v])->first()->toArray();
                //echo '<pre>';print_r($detail);echo '</pre>';
            }
        }*/
        $cart_goods=CartModel::where(['uid'=>session()->get('uid')])->get()->toArray();
        //print_r($cart_goods);exit;
        if(empty($cart_goods)){
            echo '购物车中商品为空';die;
        }
        if($cart_goods){
            foreach($cart_goods as $k=>$v){
                $goods_info=GoodsModel::where(['g_id'=>$v['g_id']])->first()->toArray();
                $goods_info['buy_number']=$v['buy_number'];
                $goods_info['add_time'] = $v['add_time'];
                $list[]=$goods_info;
                //print_r($goods_info);exit;
            }
        }
        $data=[
            'list'=>$list
        ];
        return view('cart.show',$data);
    }
    /** 购物车添加 session*/
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
    /** 购物车添加商品 */
    public function goodsAdd(Request $request){
        $g_id=$request->input('g_id');
        $num=$request->input('buy_number');
        //var_dump($num) ;
        //检查库存
        $store_num=GoodsModel::where(['g_id'=>$g_id])->value('g_store');
        if($store_num<=0){
            $response=[
                'errno'=>5001,
                'msg'=>'商品已售完'
            ];
        }
        $uid=$request->session()->get('uid');
        $cart=CartModel::where(['g_id'=>$g_id,'uid'=>$uid])->first();
        //var_dump($cart);exit;
        if(!empty($cart)){
            $buy_number=$cart->buy_number;
            $number=$buy_number+$num;
            $cart_num=CartModel::where(['g_id'=>$g_id,'uid'=>$uid])->update(['buy_number'=>$number]);
            if($cart_num){
                $response=[
                    'errno'=>0,
                    'msg'=>'添加成功'
                ];
            }
        }else{
            //        $data=$request->session()->all();
//        var_dump($data);exit;
//        $uid=$request->session()->get('uid');
//        var_dump($uid);
            //写入购物车
            $data=[
                'g_id'=>$g_id,
                'buy_number'=>$num,
                'add_time'=>time(),
                'session_token'=>$request->session()->get('u_token'),
                'uid'=>$request->session()->get('uid')
            ];
            $c_id=CartModel::insertGetId($data);
            if(!$c_id){
                $response=[
                    'errno'=>5002,
                    'msg'=>'添加购物车失败，请重试'
                ];
                return $response;
            }
            $response=[
                'errno'=>0,
                'msg'=>'添加成功'
            ];
        }
        return $response;
    }
    /** 购物车删除  session */
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
    /** 购物车数据库删除 */
    public function del2($g_id){
        $res=CartModel::where(['uid'=>session()->get('uid'),'g_id'=>$g_id])->delete();
        echo '删除成功';
        header('refresh:2;/cart/cart');
    }
}
