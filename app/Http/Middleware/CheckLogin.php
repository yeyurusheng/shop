<?php

namespace App\Http\Middleware;

use Closure;

class CheckLogin
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
        $token=$request->session()->get('u_token');
        //echo $token;
        if(!$token){
            echo '请先登录';
            header('refresh:2;url=/login');
        }
        return $next($request);
    }
}
