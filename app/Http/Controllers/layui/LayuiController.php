<?php

namespace App\Http\Controllers\layui;

use App\Model\OrderDetailModel;
use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LayuiController extends Controller
{
    public function pay(Request $request)
    {
        $order_sn = $request->input('order_sn');
        $name = $request->input('u_name');
        $namedata = UserModel::where('u_name', $name)->first();
//            var_dump($order_sn);exit;
//            var_dump($namedata);
        $id = $namedata['uid'];
//            var_dump($id);
        $where = [];
        if (!empty($order_sn)) {
            $where = [
                'order_sn' => $order_sn
            ];
        }
        if (!empty($name)) {
            $where = [
                'uid' => $id
            ];
        }
        if (!empty($order_sn) && !empty($name)) {
            $where = [
                'order_sn' => $order_sn,
                'uid' => $id
            ];
        }
//            var_dump($where);
        $info = OrderDetailModel::where($where)->get()->toArray();
//            var_dump($info);exit;
        foreach ($info as $k => $v) {
            if ($v['status'] == 1) {
                $info[$k]['status'] = '未支付';
            } elseif ($v['status'] == 2) {
                $info[$k]['status'] = '已支付';
            }elseif ($v['status'] == 3){
                $info[$k]['status'] = '已取消';
            }
            $info[$k]['add_time'] = date('Y-m-d H:i:s', $info[$k]['add_time']);

        }
       return [
            'code'=>0,
            'msg'=>'',
            'data' => $info
        ];
        }
    public function layui(){
        return view ('layui_admin.pay');
    }
    public function update(Request $request){

    }
    public function update_status($order_sn){
        //商户订单号，商户网站订单系统中唯一订单号
        $out_trade_no = $order_sn;

        //支付宝交易号
        $trade_no ='';
        //请二选一设置
        //构造参数AlipayTradeQueryContentBuilder
        $RequestBuilder = new \AlipayTradeCloseContentBuilder();
        $RequestBuilder->setOutTradeNo($out_trade_no);
        $RequestBuilder->setTradeNo($trade_no);
        $config=config('alipay');
        $aop = new \AlipayTradeService($config);

        /**
         * alipay.trade.query (统一收单线下交易查询)
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @return $response 支付宝返回的信息
         */
        $response = $aop->Query($RequestBuilder);
        var_dump($response);
    }
}
