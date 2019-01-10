<?php

namespace App\Http\Controllers\Pay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PayController extends Controller
{
    /** 订单支付 */
    public function show(){
        echo '支付';
    }
}
