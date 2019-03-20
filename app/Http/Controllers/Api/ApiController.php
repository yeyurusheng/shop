<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function api(){
        $array = [
            '0'=>'1',
            '1'=>'1',
            '2'=>'1',
            '3'=>'1',
            '4'=>'1',
            '5'=>'1',
            '6'=>'1',
            '7'=>'1',
            '8'=>'1',
            '9'=>'1',
            '10'=>'2',
            '11'=>'2',
        ];
        return $array;

    }
}
