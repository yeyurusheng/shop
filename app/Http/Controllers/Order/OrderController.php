<?php

namespace App\Http\Controllers\Order;

use App\Model\CartModel;
use App\Model\GoodsModel;
use App\Model\OrderDetailModel;
use App\Model\OrderModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /** 订单展示 */
    public function show(){
        $order_show=OrderModel::where(['uid'=>session()->get('uid')])->get();
        $data=[
            'list'=>$order_show
        ];
        return view('order.show',$data);
    }

    /** 订单添加 */
    public function add(Request $request){
        //查询购物车中的商品
        $cart_goods=CartModel::where(['uid'=>$request->session()->get('uid')])->orderBy('c_id','desc')->get();
        //print_r($cart_goods);exit;
        if(empty($cart_goods)){
            die('购物车中没有商品');
        }
        //生成订单号
        $order_sn=OrderModel::generateOrderSN();
        //var_dump($order_sn);
        $amount=0;
        foreach($cart_goods as $k=>$v){
            $cart_num=$v->buy_number;
            $goods_info = GoodsModel::where(['g_id'=>$v['g_id']])->first();
            $goods_num=intval($goods_info->g_store);
            $update=[
                'g_store'=>$goods_num-$cart_num
            ];
            GoodsModel::where(['g_id'=>$v['g_id']])->update($update);
            $goods_info['buy_number'] = $v['buy_number'];
            $detail_data=[
                'order_sn'=>$order_sn,
                'g_id'=>$v['g_id'],
                'g_name'=>$goods_info->g_name,
                'g_price'=>$goods_info->g_price,
                'buy_number'=>$goods_info['buy_number'],
                'uid'=>$request->session()->get('uid'),
                'add_time'=>time()
            ];
            OrderDetailModel::insertGetId($detail_data);
            $list[] = $goods_info;
            //计算订单价格 = 商品数量 * 单价
            $amount += $goods_info['g_price'] * $v['buy_number'];
            /*$goods_info = GoodsModel::where(['g_id'=>$v['g_id']])->first();
            $new_goodsinfo = json_decode(json_encode($goods_info),true);
//            print_r($new_goodsinfo);exit;
            $new_goodsinfo['buy_number'] = $v['buy_number'];
            $list[] = $goods_info;

            //计算订单价格 = 商品数量 * 单价
            $order_amount = '';

//            $number = ($v['buy_number']);
            $price = $new_goodsinfo['g_price'];

            $order_amount .= $price *intval($v['buy_number']) ;

            //echo $order_amount;*/
        }
        $data=[
            'order_sn'=>$order_sn,
            'uid'=>$request->session()->get('uid'),
            'add_time'=>time(),
            'order_amount'=>$amount,
        ];
        $o_id=OrderModel::insertGetId($data);
        if(!$o_id){
            echo '生成订单失败';
        }
        echo '下单成功，订单号:'.$o_id.' 跳转支付';
        //清空购物车
        CartModel::where(['uid'=>$request->session()->get('uid')])->delete();
        //return $amount;
        header('refresh:2;url=/order/show');
    }

    /** 订单详情展示 */
    public function detailShow(){
        $detail_show=OrderDetailModel::where(['uid'=>session()->get('uid')])->get();
        $data=[
            'list'=>$detail_show
        ];
        return view('order.detail_show',$data);
    }

    /** 订单取消 */
    public function cancel($order_sn){
        $cancel=OrderModel::where(['order_sn'=>$order_sn])->update(['status'=>3]);
        if($cancel){
            echo '取消订单成功';
           $g_id=OrderDetailModel::where(['order_sn'=>$order_sn])->get()->toArray();
           foreach($g_id as $k=>$v){
               $where=[
                   'g_id'=>$v['g_id']
               ];
               $buy_number=intval(OrderDetailModel::where($where)->value('buy_number'));
               $goods_num=intval(GoodsModel::where($where)->value('g_store'));
               $update=[
                   'g_store'=>$buy_number+$goods_num
               ];
               $goods=GoodsModel::where($where)->update($update);
           }


        //$g_id=OrderDetailModel::where(['d_id'=>1])->get();
//        $goods_id=$g_id->g_id;
        }
//        if($cancel){
//            echo '删除成功';
//            header('refresh:2,url=/order/show');
//        }else{
//            echo '删除失败';
//        }
    }
}
