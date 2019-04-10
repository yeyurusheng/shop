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
    //验证码   随机数
    public function showCode(Request $request,$sid){
        session_id($sid);
        session_start();
        $rand = rand(1000,9999);
        //var_dump($rand);exit;
        header('content-type:image/png');
        //创建一个400*30 的画布
        $im = imagecreatetruecolor(120,60);
        //创建几个颜色
        $white = imagecolorallocate($im,255,255,255);
        $black = imagecolorallocate($im,0,0,0);
        $red = imagecolorallocate($im,230,30,40);
        //填充画布的背景色
        imagefilledrectangle($im,0,0,120,60,$white);
        $font = '/data/wwwroot/font/comicbd.ttf';
//        $i=0;
//        while($i<4){
//
            //if(is_numeric($rand[$i])){
                imagettftext($im,20,rand(-30,30),35,40,$black,$font,$rand);
            // }else{
//                imagettftext($im,20,0,10+20*$i,24,$black,$font ,$rand[$i]);
//            }
//            $i++;
//        }
        imagepng($im);
        imagedestroy($im);exit;
    }


    /**
     * 验证码   加法
     */
    public function addCode(Request $request,$sid){
        session_id($sid);
        session_start();

        $a = rand(1,9);
        $b = rand(1,9);
        $_SESSION['vcode'] = $a*$b;
        $rand = $a.'*'.$b.'=?';
        //var_dump($rand);exit;
        header('content-type:image/png');
        //创建一个400*30 的画布
        $im = imagecreatetruecolor(120,40);
        //创建几个颜色
        $white = imagecolorallocate($im,255,255,255);
        $black = imagecolorallocate($im,0,0,0);
        $red = imagecolorallocate($im,230,30,40);
        //填充画布的背景色
        imagefilledrectangle($im,0,0,120,40,$white);
        $font = '/data/wwwroot/font/comicbd.ttf';
        $i=0;
        while($i<5){
           // var_dump($i);echo '<hr>';
            if(is_numeric($rand[$i])){
            imagettftext($im,20,rand(-30,30),10+20*$i,28,$black,$font,$rand[$i]);
            }else{
                imagettftext($im,20,0,10+20*$i,30,$black,$font ,$rand[$i]);
            }
            $i++;
            //var_dump($i);
        }

        imagepng($im);
        imagedestroy($im);exit;
    }
}
