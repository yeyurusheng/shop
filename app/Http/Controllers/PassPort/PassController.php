<?php

namespace App\Http\Controllers\PassPort;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PassController extends Controller
{
    public function index(){
        $url = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
        $data = [
            'url'=>$url
        ];
        return view ('pass.index',$data);
    }
}
