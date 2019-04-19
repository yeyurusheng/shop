<?php

namespace App\Http\Controllers\layui;

use App\Model\OrderDetailModel;
use App\Model\OrderModel;
use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

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
        $aop = new AopClient ();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = '2016092300576110';
        $aop->rsaPrivateKey = 'MIICXQIBAAKBgQDgZzvu112qZEgNUwQMXku5JTPziAAjV8FpXN5H0xRmUPbIJJo5G19J+od0QnsV+uWvTXdxR4PurAI0BiTR8yBMN9ft/HgdRmlhjcnb0lU1aHAuhzIN7BxuXrhtSBDWQpxrDG/A3Ee3YT35ktzuoRNOaGNBvHT9OngMWwpTQXn4LQIDAQABAoGBAKFCjpc8vVrNZdntZmNdAB201niTq0W6sor86d/DmE1OsYlyhEG1oeyP1Pd/LuTEwHoRuVv2gKDri0nHgH4/sVy7dr7J/F5AYyukm9vKzIAyLRp7mIXYA1huXfRmwlw5vTtTF1hfa9/dXdCM/aLJQty9EHMZx3bpPqVJsAkADsoBAkEA/DDoL/6SSpHJ2gkjOr4QZCEYeYXp/Dkna72EWi2DmxWNHcYYLjOVBej9iwTdTznatfYvdTNYsLwepdDXhP1G0QJBAOPK4tURWZoJA4w6IXygsfYETxpPdvLAWxLdJpD3RD0FxiTU0kJMUlCPZaRb1JOGiSLDx+fg4idAnHmTqHBjap0CQB9ihsLST6pwEkrMMFIzLR8I717QR5pYEovZ/gqq92HpgLJf4Mp/KOCfak5OwKwHayySAr33MeZswvOn1ep7CsECQE9pSGCdFs6DO/Bjx47J+qBYajcy4rXH5zgRTsOU3/4iCCyI4O/p6XxaMUX2GYqAiUhMVmF43X5voN0lY8AieGUCQQDztC6EsR52+vCQ7RCVjRZI1FlFmPUQWNbPhWJ3OJ9Y3GEpIdBQXchiTuTLVuJ/mLNc/FfRy1DKAk3jluffeddc';
        $aop->alipayrsaPublicKey='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAoSlz5bKzetLyascaqtKTUcCiQoNiL6TE8Xw2SE9M/JwXS9OBzyRrch27B85KLH4+SSGwTkydAcktrWZK15Xm+yDXqy551vxKcbQwO+0cY283qu3/EH2TNz9FOY1XYJu6Or9UNj9oe2tz9IZxnG2a2GTMX0YTDP4ie3mQTlmuxyVvAWkbSHvMtIXT4dcpKdSeTjxm4TAel0V6mFDOzO/eChLVvWhrqlRrKw0rAMPz1UiBnw1rxmnUeaxLu3iXRwbrqf6g2u9rclHIqNT0V9246hx5btsckJSUYYGhzP7xIydKx7g0f1+S68uCyctGagybQHS9Mxnv8g05BHqIyD0kVQIDAQAB';
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset='GBK';
        $aop->format='json';
        $request = new AlipayTradeQueryRequest ();
        $request->setBizContent("{" .
            "\"out_trade_no\":\"20150320010101001\"," .
            "\"trade_no\":\"2014112611001004680 073956707\"," .
            "\"org_pid\":\"2088101117952222\"" .
            "  }");
        $result = $aop->execute ( $request);

        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        if(!empty($resultCode)&&$resultCode == 10000){
            echo "成功";
        } else {
            echo "失败";
        }

        //return view('layui_admin.update');
    }
}
