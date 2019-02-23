<?php

namespace App\Admin\Controllers;

use App\Model\WeixinUser;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp;
use Illuminate\Http\Request;

class WeixinGroup extends Controller
{
    use HasResourceActions;
    protected $redis_weixin_access_token = 'str:weixin_access_token';

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body(view('weixin.chat'));
    }
    /**
     *  获取access_token
     */
    public function getWxAccessToken(){
        $token = Redis::get($this->redis_weixin_access_token);
        if(!$token){
            //无缓存 请求微信接口
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WEIXIN_APPID').'&secret='.env('WEIXIN_APPSECRET');
            $data = json_decode(file_get_contents($url),true);
            //记录缓存
            $token=$data['access_token'];
            Redis::set($this->redis_weixin_access_token,$token);
            Redis::setTimeout($this->redis_weixin_access_token,3600);
        }
    }
    public function groupText(Request $request){
        $text = $request->input('group');
        //获取access_token
        $access_token = $this->getWxAccessToken();
        //请求群发接口
        $url='https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$access_token;
        $client=new GuzzleHttp\Client(['base_uri'=>$url]);
        $data=[
            "filter"=>[
                "is_to_all"=>true, //用于设定是否向全部用户发送，值为true或false，选择true该消息群发给所有用户，选择false可根据tag_id发送给指定群组的用户
                "tag_id"=>2
            ],
            "text"=>[
                "content"=>$text,
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

}