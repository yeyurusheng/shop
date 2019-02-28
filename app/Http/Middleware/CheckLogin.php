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
        /*if(!$token){
            echo json_encode([
                'errno'=>301,
                'url'=>'/login'
            ]);
            die;
        }*/
        if(empty($_COOKIE['uid'])){
            echo 'No UID ，请先登录';echo '</br>';
            header('Refresh:2;url=/melogin');
        }
        return $next($request);
    }
}
