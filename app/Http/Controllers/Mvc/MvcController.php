<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/4 0004
 * Time: 10:33
 */

namespace App\Http\Controllers\Mvc;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class MvcController extends Controller{
    public function mvc(){
        return view('mvc.test');
    }
}