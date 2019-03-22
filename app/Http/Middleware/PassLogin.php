<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class PassLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(empty($_COOKIE['uid'])){
            echo 'No UID ，请先登录';echo '</br>';
            header('Refresh:2;url=http://passwei.shop.com/login');
        }
        if(isset($_COOKIE['uid']) && isset($_COOKIE['token'])){
            $redis_key =  $redis_key = "redis:login:token:".$_COOKIE['uid'];
            $r_token = Redis::get($redis_key);
            if ($r_token==$_COOKIE['token']){
                //token 有效
                $request->attributes->add(['is_login'=>1]);
            }else{
                //Tokken无效
                $request->attributes->add(['is_login'=>0]);
            }
        }else{
            $request->attributes->add(['is_login'=>0]);
            echo '没有登录';
        }
        return $next($request);
    }
}
