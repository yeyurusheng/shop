<?php

namespace App\Http\Controllers\Vcode;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class VcodeController extends Controller
{
    public function sid(){
        session_start();
        $sid = session_id();
        var_dump($sid);
    }
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
        //填充画布的背景色
        imagefilledrectangle($im,0,0,399,29,$white);

        $i=0;
        while($i<5){
            if(is_numeric($rand[$i])){
                imagettftext($im,20,rand(-30,30),10+20*$i,24,$black,'',$rand[$i]);
            }else{
                imagettftext($im,20,0,10+20*$i,24,$black,'' ,$rand[$i]);
            }
            $i++;
        }
        imagepng($im);
        imagedestroy($im);exit;
    }
}
