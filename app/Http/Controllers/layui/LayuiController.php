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
    public function update_status(){
        $aop = new \AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = '2016092300576110';
        $aop->rsaPrivateKey = 'MIIEowIBAAKCAQEA2KKiTDUQaQNmdi2Vr1DQldwXXQlm7dMxux5QN1mxZ5kzzL6eE6ZO6PmfHRZRMP8wde5HUTjpvBPuxBIdBzZ7fmteuTCu3/Kct6X/GMp11a52qJUWyc5uN2itg0IN2xa0D8zuKCNnwd2CsgX+WqG5xDt225Sa+wflmJIhX3xE24ZusF+eVCdPD7b9XqwjNDHpa4+UUTPnSDor+O/wpfIQET3ZzAKgQFQkxHhhoPK57CmpL8NariN9lyPQJ+kspZZ/MASAi0RcwRrKRPZlKnp40/xMC1HHZ8YOYGxuE8XBjYqgGGopBP+qjOeGvpGdLfEgozXBPni/rfmY174KyGP1HwIDAQABAoIBAAGNuQg+4S0/nv59+TLWq4hHmctLA/f0qcijwQOEmycZDCwNueh+Kta045U70b0+N0TBPrWwaMy2f+HcF2Uz2NLhRj6hwL9OaB1RDXFKEmpD5An1d8Jevp+bGpN0Z9EIaqYqXBLhnh7fzc4lCNvxiPmFS6bre+kM93KOqbqbk4rXepZ+CNxydntirbJjNXdXE/kj2q4jhXaz5VJ8T1OZfW3/XocjM6wogE1koDy6QzzhTQk5Kahglr5yjilX/Fx4YMBKbZe6IPtPzdK4QJMpkr10CNqP3+MYNaZeaWaplhTgl0puwrEhN7YJBH7qlazJbMBkfcmjdr5la+oVshBEjwECgYEA8+kRXuDlmyYG8PFtEESH5H86tqUUepVx3Ntvlx4MCLcKPJLq+0h/jENIg8/wQcRpeW5D6TqVFIl81qwq+sVAWJpPs/0UMBYfiF+9UeOlMBSxfUI9llJxBahz7cbgZoe0Ze8Tq7BzvO3wjJ2YSUhI0ESp+Z6V9wVfChdGrsAABd8CgYEA4196LKsurXWVUHnTTo1TPopiCUAqiOZU0rhkIO7xc3pygs1kjELm3TEocTt+rOldGa7e076Wg7kQT5kTU2Xs6CCAkrwX0o466V8wvqAUhTFA1hYG3/FUoOoMPCzERPuqj6nax3d0l/flQhALhlmewtoOZ7CiTaMRIP7FtnhBeMECgYBLeQf1+tMxYd/t+FaOHRaLns7VK1md4NZdx8zMzvBcVf8l5TQu0y0cgslSeCpYv2T/vR3ockclFrH5X0bhV93kTMNy51Gy23WgZjeXukAoE48kOjYCaLouTQSqQ8q2DzHEBOlqS+xUPrAbYrx3mJVnVIfgBLFUwWW6Ip+tfR3EswKBgBa9A6DX9bEPI0GLDWC7rWmBuVAnhJlwP2dbXVgVzTu5MR53n+iPADt6oQrB4mc0Z0UcQdh3JOJDbagoLNCYTtrf9Tu3Z8J8ytNv25YUuDihtlQym7lljwsQnOyfpXkomeQIHoL4JUbVWa4DoMNszv25hNtFxNVyy1G7aNZYrGOBAoGBAOsVsIPD/ugR5A8VRQBLLYSa1L7gS6nafMVQdBevDQcONzL+v3TP9WCY0M20YBAIFvnYTJt5V/uuxzm00fD3DxhxP7mK3Zi2AH6g6BetXuGShj5XG6rtdnVL10aSbhrAWtD7BFMrjZtwmzOXufqqvHMf2QYgdkh43N0srY2x2qnY';
        $aop->alipayrsaPublicKey='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAoSlz5bKzetLyascaqtKTUcCiQoNiL6TE8Xw2SE9M/JwXS9OBzyRrch27B85KLH4+SSGwTkydAcktrWZK15Xm+yDXqy551vxKcbQwO+0cY283qu3/EH2TNz9FOY1XYJu6Or9UNj9oe2tz9IZxnG2a2GTMX0YTDP4ie3mQTlmuxyVvAWkbSHvMtIXT4dcpKdSeTjxm4TAel0V6mFDOzO/eChLVvWhrqlRrKw0rAMPz1UiBnw1rxmnUeaxLu3iXRwbrqf6g2u9rclHIqNT0V9246hx5btsckJSUYYGhzP7xIydKx7g0f1+S68uCyctGagybQHS9Mxnv8g05BHqIyD0kVQIDAQAB';
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset='GBK';
        $aop->format='json';
        $request = new  \AlipayTradeQueryRequest();
        $request->setBizContent("{" .
            "\"out_trade_no\":\"272281542426476396\"," .
            "\"trade_no\":\"2019040422001187411026594357\"," .
            "\"org_pid\":\"\"" .
            "  }");
        $result = $aop->execute() ( $request);

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
