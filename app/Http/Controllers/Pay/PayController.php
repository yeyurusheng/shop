<?php

namespace App\Http\Controllers\Pay;

use App\Model\OrderModel;
use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PayController extends Controller
{
    /** 订单支付 给用户积分 */
    public function show($order_sn){
        //获取订单的总价格
        $order_amount=OrderModel::where(['order_sn'=>$order_sn])->value('order_amount');
        $update=[
            'integral'=>$order_amount
        ];
        $integral=UserModel::where(['uid'=>session()->get('uid')])->update($update);
        if($integral){
            echo '积分添加成功';
        }
    }
}
