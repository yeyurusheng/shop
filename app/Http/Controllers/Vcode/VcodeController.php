<?php

namespace App\Http\Controllers\Vcode;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VcodeController extends Controller
{
    //验证码
    public function showCode(Request $request,$sid){
        session_id($sid);
        session_start();
        $rand = rand(1000,9999);
        header('content-type:image/png');
        //创建一个400*30 的画布
        $im = imagecreatetruecolor(400,30);
        //创建几个颜色
        $white = imagecolorallocate($im,255,255,255);
        $black = imagecolorallocate($im,0,0,0);
        imagefilledrectangle($im,0,0,399,29,$white);
        $text = rand(1,1,1,1,4,4,4,4);
    }
}
