<?php

namespace App\Http\Middleware;
use Closure;
use http\Env\Response;
use Illuminate\Support\Facades\Redis;

class CheckAPIRequest
{
    private $_app_arr=[];
    public function handle($request, Closure $next)
    {
        $client_data=$request->post('data');
        //解密数据
        $this->_decrypt($client_data);
        //接口防刷
        $info=$this->_checkApiAccessCount();
        if($info['status']==1000){
            return $next($request);
        }else{
            return response($client_data);
        }
        //验签
        $data=$this->_checkClientSign( $request );
        if($data['status']==1000){
            return $next($request);
        }else{
            return response($data);
        }
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
            if(time()-$join_time>=30 * 60){
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
        if($count>=100){
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