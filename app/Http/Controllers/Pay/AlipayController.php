<?php

namespace App\Http\Controllers\Pay;

use App\Model\OrderModel;
use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AlipayController extends Controller
{
    public $app_id = '2016092500593666';
    public $gate_way = 'https://openapi.alipaydev.com/gateway.do';
    public $notify_url = 'http://wei.tactshan.com/pay/alipay/notify';
    public $rsaPrivateKeyFilePath = './key/priv.key';
}
