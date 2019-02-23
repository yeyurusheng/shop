<?php

namespace App\Http\Controllers\Weixin;

use App\Model\WeixinMedia;
use App\Model\WeixinUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class WeixinController extends Controller{
    protected $redis_weixin_access_token = 'str:weixin_access_token';

    public function test()
    {
        //echo __METHOD__;
        //$this->getWXAccessToken();
        $this->getUserInfo(1);
    }

    /**
     * 首次接入
     */
    public function validToken1()
    {
        echo $_GET['echostr'];
    }

    /**
     * 接收事件推送
     */
    public function validToken(){
        $data = file_get_contents("php://input");
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/wx_event.log',$log_str,FILE_APPEND);
    }

    /**
     * 刷新token
     */
    public function refreshToken(){
        Redis::del($this->redis_weixin_access_token);
        echo $this->getWXAccessToken();
    }

    /**
     * 下载图片文件
     */
    public function dwImage($media_id){
        $client = new GuzzleHttp\Client();
        //echo '<pre>';var_dump($client);echo '</pre>';
        //获取access_token
        $access_token = $this->getWXAccessToken();
        //拼接下载图片的URL
        $url='https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$access_token.'&media_id='.$media_id;
        //echo $url;
        //使用GuzzleHttp下载文件
        $response = $client->get($url);
        //获取文件名称
        $file_info=$response->getHeader('Content-disposition');
        $file_name=substr(rtrim($file_info[0],'"'),-20);
        $WxImageSavePath='wx/image/'.$file_name;
        //保存路径/home/wwwroot/shop/storage/app/wx/images
        //保存图片
        $res=Storage::disk('local')->put($WxImageSavePath,$response->getBody());
        if($res){
            //保存成功
            //echo '保存图片成功';

        }else{
            //保存失败
            //echo '保存图片失败';
        }
        return $file_name;

    }

    /**
     * 下载语音文件
     */
    public function dlVoice($media_id){
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->getWXAccessToken().'&media_id='.$media_id;
        //echo $url ;
        //保存语音
        $client = new GuzzleHttp\Client();
        $response = $client->get($url);
        //var_dump($response);
        //获取文件名
        $file_info=$response->getHeader('Content-disposition');
        $file_name = substr(rtrim($file_info[0],'"'),-20);
        $wx_voice_path = 'wx/voice/'.$file_name;
        //保存语音
        $res=Storage::disk('local')->put($wx_voice_path,$response->getBody());
        if($res){

        }else{

        }
        return $file_name;

    }

    /**
     * 接收微信服务器事件推送
     */
    public function wxEvent(){
        $data = file_get_contents("php://input");
        //解析XML
        $xml = simplexml_load_string($data);        //将 xml字符串 转换成对象
        //var_dump($xml);exit;

        //记录日志
        $log_str=date('Y-m-d H:i:s')."\n".$data."\n<<<<<<";
        file_put_contents('logs/wx_event.log',$log_str,FILE_APPEND);


        $event = $xml->Event;               //事件类型
        $openid = $xml->FromUserName;               //用户openid

        //处理用户发送信息
        if(isset($xml->MsgType)){
            if($xml->MsgType=='text'){   //用户发送文本信息
                $msg = $xml->Content;
                $xml_response = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. $msg. date('Y-m-d H:i:s') .']]></Content></xml>';
                echo $xml_response;

                //echo '文本';
            }elseif($xml->MsgType=='image'){   //用户发送图片信息
                //判断是否需要保存图片信息
                if(1){   //下载图片信息
                    //echo $file_name;exit;
                    $Media_id=$this->dwImage($xml->MediaId);
                    $xml_response = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. date('Y-m-d H:i:s') .']]></Content></xml>';
                    echo $xml_response;
                    //写入数据库
                    $data=[
                        'openid'   =>$openid,
                        'add_time' =>time(),
                        'msg_type' =>'image',
                        'media_id' =>$xml->MediaId,
                        'format'   =>$xml->Format,
                        'msg_id'   =>$xml->MsgId,
                        'local_file_name' => $Media_id,
                        'local_file_path' => '/data/wwwroot/shop/storage/app/wx/image/'.$Media_id,
                    ];
                    WeixinMedia::insertGetId($data);
                }
            }elseif($xml->MsgType=='voice'){   //处理语音文件
                $this->dlVoice($xml->MediaId);
                echo '语音';
            }elseif($xml->MsgType=='event'){   //处理事件类型
                if($event=='subscribe'){       //扫码关注事件
                    $sub_time = $xml->CreateTime;    //扫码关注时间
                    //获取用户信息
                    $user_info = $this->getUserInfo($openid);
                    //保存用户信息
                    $u = WeixinUser::where(['openid'=>$openid])->first();
                    if($u){     //用户不存在
                        echo '用户已存在';
                    }else{
                        $user_data = [
                            'openid' => $openid,
                            'add_time' => time(),
                            "nickname"=>$user_info["nickname"],
                            'sex' => $user_info['sex'],
                            'headimgurl' => $user_info['headimgurl'],
                            'subscribe_time' => $sub_time,
                        ];
                    }
                    $id = WeixinUser::insertGetId($user_data);      //保存用户信息

                }elseif($event=='CLICK'){
                    if($xml->EventKey=='kefu'){
                        $this->kefu($openid,$xml->ToUserName);
                    }
                }
            }
        }
    }



    /**
     * 客服处理
     */
    public function kefu($openid,$from){
        //文本信息
        $xml_response = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$from.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. '欢迎来到这里, 现在是北京时间'. date('Y-m-d H:i:s') .']]></Content></xml>';
        echo $xml_response;
    }

    /**
     * 获取微信access_token
     * @return mixed
     */
    public function getWXAccessToken(){
        $token=Redis::get($this->redis_weixin_access_token);
        if(!$token){
            //无缓存 请求微信接口
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WEIXIN_APPID').'&secret='.env('WEIXIN_APPSECRET');
            $data=json_decode(file_get_contents($url),true);
            //记录缓存
            $token=$data['access_token'];
            Redis::set($this->redis_weixin_access_token,$token);
            Redis::setTimeout($this->redis_weixin_access_token,3600);

        }

        return $token;
    }
    /**
     * 获取用户信息
     */
    public function getUserInfo($openid){
        //$openid='wxc28bbb4fa8c63d2b';
        $access_token = $this->getWXAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $data = json_decode(file_get_contents($url),true);
        //echo '<pre>';print_r($data);echo'</pre>';
        return $data;
    }
    /**
     * 创建服务号菜单
     */
    public function createMenu(){
        //获取access_token拼接请求接口
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->getWXAccessToken();

        //请求微信接口
        $client = new GuzzleHttp\Client(['base_uri' => $url]);
        $data=[
            "button"    =>[
                [   "type"=>"view",      //view类型 跳转指定
                    "name"=>"wei",
                    "url" =>"https://www.baidu.com"
                ],
                [
                    "type"  =>  "click",
                    "name"  =>  "客服",
                    "key"   =>  "kefu"
                ]
            ]
        ];
        $body = json_encode($data,JSON_UNESCAPED_UNICODE);    //处理中文编码
        $r = $client->request('POST', $url, [
            'body' => $body
        ]);
        // 解析微信接口返回信息
        $response_arr=json_decode($r->getBody(),true);
        if($response_arr['errcode']==0){
            echo "菜单创建成功";
        }else{
            echo "菜单创建失败，请重试";echo '<br>';
            echo $response_arr['errmsg'];
        }
    }

    /**
     * 群发消息
     */
    public function massText(){
        $access_token = $this->getWXAccessToken();
        //echo $access_token;exit;
        $url='https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$access_token;
        //echo $url ;exit;
        $client=new GuzzleHttp\Client(['base_uri'=>$url]);
        $data=[
            "filter"=>[
            "is_to_all"=>true, //用于设定是否向全部用户发送，值为true或false，选择true该消息群发给所有用户，选择false可根据tag_id发送给指定群组的用户
              "tag_id"=>2
           ],
           "text"=>[
                    "content"=>"欢迎来到"
           ],
            "msgtype"=>"text"

        ];
        $body = json_encode($data,JSON_UNESCAPED_UNICODE);    //处理中文编码
        $res = $client->request('post',$url,['body'=>$body]);
        //解析返回的信息
        $response_arr = json_decode($res->getBody(),true);
        if($response_arr['errcode']==0){
            echo "群发消息成功";
        }else{
            echo "群发消息失败，请重试";echo '<br>';
            echo $response_arr['errmsg'];
        }
    }
    /**
     * 上传素材
     */
    public function upMaterial(){
        $url = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$this->getWXAccessToken().'&type=image';
        $client = new GuzzleHttp\Client();
        $response = $client->request('POST',$url,[
            'multipart' => [
                [
                    'name' => 'username',
                    'contents' => 'wei',
                ],
                [
                    'name'  => 'media',
                    'contents'  => fopen('OaDbWfmCy9dIZk2J.jpg','r')
                ]
            ],

        ]);
        $body = $response->getBody();
        echo $body;echo '<hr>';
        $d = json_decode($body,true);
        echo '<pre>'; print_r($d);echo '</pre>';

    }
    /**
     * 上传素材
     */
    public function upMaterialTest($file_path){
        $url = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$this->getWXAccessToken().'&type=image';
        $client = new GuzzleHttp\Client();
        $response = $client->request('POST',$url,[
            'multipart' => [
                [
                    'name'     => 'username',
                    'contents' => 'zhangsan'
                ],
                [
                    'name' => 'media',
                    'contents'  => fopen($file_path,'r')
                ]
            ]
        ]);
        $body = $response->getBody();
        echo $body;echo '<hr>';
        $d = json_decode($body,true);
        echo '<pre>';print_r($d);echo '</pre>';
    }
    /**
     * 上传表单
     */
    public function formShow(){
        return view ('weixin.form');
    }

    /**
     * 上传永久素材
     */
    public function formTest(Request $request){
//        echo '<pre>';print_r($_POST);echo '</pre>';echo '<hr>';
//        echo '<pre>';print_r($_FILES);echo '</pre>';echo '<hr>';
        //保存文件
        $img_file = $request-> file('media');
        //echo '<pre>';print_r($img_file);echo '</pre>';echo '<hr>';

        $img_origin_name = $img_file->getClientOriginalName();    //获取文件的原名
        echo 'originName: '.$img_origin_name;echo '</br>';
        $file_ext = $img_file->getClientOriginalExtension();    //获取文件扩展名
        echo 'ext: '.$file_ext;echo '</br>';

        //重命名
        $new_file_name = str_random(15).'.'.$file_ext;
        //echo 'new_file_name: '.$new_file_name;echo '</br>';

        //文件保存路径
//        $wx_material_path = 'form_test'.$new_file_name;
//        echo 'wx_material_path: '.$wx_material_path;echo '</br>';

        //保存文件
        $save_file_path = $request->media->storeAs('form_test',$new_file_name);      //返回保存成功之后的文件路径
        //echo 'save_file_path: '.$save_file_path;echo '</br>';

        //上传至微信永久素材
        $this->upMaterialTest($save_file_path);
    }

    /**
     * 获取永久素材
     */
    public function materialList(){
        $client = new GuzzleHttp\Client();
        //echo '<pre>';print_r($_GET);echo '</pre>';exit;
        $type = $_GET['type'];
        $offset = $_GET['offset'];
        $url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.$this->getWXAccessToken();

        $body = [
            "type"      => $type,
            "offset"    => $offset,
            "count"     => 20
        ];
        $response = $client->request('POST', $url, [
            'body' => json_encode($body)
        ]);

        $body = $response->getBody();
        echo $body;echo '<hr>';
        $arr = json_decode($response->getBody(),true);
        echo '<pre>';print_r($arr);echo '</pre>';
    }
    /**
     * 私聊
     */
    public function privateChat(){
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$this->getWXAccessToken();
        $client = new GuzzleHttp\Client();
        $body = [
            'touser' =>'oJoCw505tKb-F1dQ2wXxxpTbOej4',
            'msgtype'=>'text',
            'text'=>[
                'contents'=>'你好王梦薇'
            ]
        ];
        $response = $client->request('POST',$url,[
            'body'=>json_decode($body)
        ]);
        echo $body;echo '<hr>';
    }
}
