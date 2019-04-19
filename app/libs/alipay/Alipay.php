<?php
namespace app\Libs\alipay;

use AlipayTradePagePayContentBuilder;
use AlipayTradeQueryContentBuilder;
use AlipayTradeService;

require ('AopSdk.php');

class Alipay
{
    public function payOrder($data)
    {
        if (!$data)
        {
            exit('param is numm');
        }
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = trim($data['out_trade_no']);

        //订单名称，必填
        $subject = trim($data['subject']);

        //付款金额，必填
        $total_amount = trim($data['total_amount']);

        //商品描述，可空
        $body = trim($data['body']);

        //构造参数
        $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);
        $config = config('alipay');
        $aop = new \AlipayTradeService($config);

        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
         */
        $response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);

        //输出表单
        return $response;
    }

    /**
     * 查询
     */
    public function alipayClose($data)
    {
        //商户订单号，商户网站订单系统中唯一订单号
        $out_trade_no = trim($data['out_trade_no']);

        //支付宝交易号
        $trade_no = '';
        //请二选一设置
        $RequestBuilder = new \AlipayTradeQueryContentBuilder();
        $RequestBuilder->setOutTradeNo($out_trade_no);
        $RequestBuilder->setTradeNo($trade_no);
        $config = config('alipay');
        $aop = new \AlipayTradeService($config);

        /**
         * alipay.trade.query (统一收单线下交易查询)
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @return $response 支付宝返回的信息
         */
        $response = $aop->Query($RequestBuilder);

        return $response;
    }

}
