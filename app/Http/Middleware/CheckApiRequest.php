<?php

namespace App\Http\Middleware;
use Closure;
use http\Env\Response;
use Illuminate\Support\Facades\Redis;

class CheckApiRequest
{
    private $_app_arr=[];
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $client_data=$request->post('data');
        //解密数据
        $this->_decrypt($client_data);
        //接口防刷
        $info=$this->_checkApiAccessCount();
        if($info['status']==1000){
            echo '后置中间件';
            return $response;
        }else{
            return response($client_data);
        }
        //验签
        $data=$this->_checkClientSign( $request );
        if($data['status']==1000){
            $response = $next($request);
            //后置操作，对返回的数据进行加密
            $api_response = [];
            return $response;
        }else{
            return response($data);
        }
    }
    /**
     * 使用对称加密方式对数据进行解密
     */
    private function _encrypt($data){
        //数据不为空界面
        if(!empty($data)){
            $encrypt_data = openssl_encrypt(json_encode($data),'AES-256-CBC','nihao',false,'1234567887654321');
        }
    }


    /**
     * 使用非对称加密方式对数加密  私钥
     */
    private function _rsaDecrypt($request){
        $i = 0;
        $all = '';
        while($substr = substr($request->post('data'),$i,172)){
            $decode_data = base64_decode($substr);
            openssl_private_decrypt($decode_data,$decrypt_data,file_get_contents('home/key/private.key'));
            $all .=$decrypt_data;
            $i+=172;
        }
        return $all;
    }
    /**
     * 解密数据
     * @param $client_data
     */
    private function _decrypt($client_data)
    {
        if( ! empty($client_data)){
            $decrypt_data = openssl_decrypt($client_data, 'AES-256-CBC', 'nihao', false, '1234567887654321');
            $this->_app_arr=json_decode($decrypt_data,true);
        }
    }

    /**
     * 使用非对称加密方式对数据进行解密
     */

    private function _rsaEncrypt($data)
    {
        $i = 0;
        $all = '';
        $str = json_decode($data);
        while($substr = substr($str,$i,117)){
            $decode_data = base64_decode($substr);
            openssl_private_encrypt($decode_data,$decrypt_data,file_get_contents('home/key/private.key'));
            $all .=$decrypt_data;
            $i+=117;
        }
        return $all;
    }

    // 生成签名
    private function _checkClientSign( $request )
    {
        if( ! empty( $this -> _app_arr)){
            //获取当前appid对应的app_key
            $map = $this->_getAppIdKey();
            //判断app_id 是否存在
            if(! array_key_exists($this->_app_arr['app_id'] , $map)){
                return [
                    'status'=>1,
                    'msg'=>'check sign fail',
                    'data'=>[]
                ];
            }
            //生成服务器端的签名
            //将数组排序
            ksort($this -> _app_arr);
            //把一个数组转化为get请求的字符串
            $str=http_build_query($this -> _app_arr);
            //把app_key拼到字符串后面
            $new_str=$str.'&app_key='.$map[$this->_app_arr['app_id']];
            //生成一个md5值
            $sign=md5($new_str);
            if($sign != $request['sign']){
                return [
                    'status'=>2,
                    'msg'=>'check sign fail',
                    'data'=>[]
                ];
            }
            return ['status' => 1000];
        }
    }

     //获取app_id所有的appkey

    private function _getAppIdKey()
    {
        return [
            md5(123)=>md5(123456)
        ];
    }

    //接口防刷
    private function _checkApiAccessCount()
    {
        //获取app_id
        $app_id=$this->_app_arr['app_id'];
        $black_key="black_list";
        //是否在黑名单中
        $join_time=Redis::zScore($black_key , $app_id);
        if(!empty($join_time)){
            //在黑名单中
            if(time()-$join_time>=30){
                Redis::zRemove($black_key ,$app_id);
                $this->_addAccessCount();
            }else{
                return
                    [
                        'status'=>3,
                        'msg'=>'调用接口次数过多',
                        'data'=>[]
                    ];
            }
        }else{
            $this->_addAccessCount();
            return ['status'=>1000];
        }
    }

    //记录访问次数
    private function _addAccessCount()
    {
        $app_id=$this->_app_arr['app_id'];
        $count=Redis::incr($app_id);
        if($count==1){
            Redis::Expire($app_id , 60);
        }
        if($count>=10){
            $black_key="black_list";
            Redis::zAdd($black_key, time() ,$app_id);
            Redis::del($app_id);
            return
                [
                    'status'=>3,
                    'msg'=>'调用接口次数过多',
                    'data'=>[]
                ];
        }
    }
}