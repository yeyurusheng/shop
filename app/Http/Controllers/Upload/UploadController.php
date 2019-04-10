<?php

namespace App\Http\Controllers\Upload;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{

    public function upload(Request $request){
        //指定文件存储路径
        //echo 111;
        $file_save_path = app_path().'/storage/uploads/'.date('ym').'/';
        //var_dump($file_save_path);exit;
        if(!is_dir($file_save_path)){
            mkdir($file_save_path,0777,true);
        }
        $file_name = time().rand(1111,9999).'tmp';
        //var_dump($file_name);
        $byte = file_put_contents($file_save_path.$file_name,base64_decode($request->post('contents')));
        //var_dump($byte);
        if($byte>0){
            //查看文件格式
            $info = getimagesize($file_save_path.$file_name);
            if(!$info){
                return ['status' => 6,'data'=>[],'msg'=>'图片内容或者格式不正确'];
            }
            //判断图片格式
            switch ($info['mime']){
                case 'image/jpeg':
                    $new_file_name = str_replace('tmp','jpg',$file_name);
                    break;
                case 'image/png':
                    $new_file_name = str_replace('tmp','jpg',$file_name);
                default:
                    return ['status' => 6,'data'=>[],'msg'=>'图片内容或者格式不正确'];
                    break;
            }
            //文件重命名
            rename($file_save_path.$file_name,$file_save_path.$new_file_name);
            $api_response = [];
            $access_path = str_replace(app_path().'/storage','',$file_save_path);
            $api_response['access_path'] = env('FILE_UPLOAD_URL').$access_path.$new_file_name;
            var_dump(env('FILE_UPLOAD_URL'));
            return ['status' => 1000,'data'=>$api_response,'msg'=>'success'];

        }
    }

}
