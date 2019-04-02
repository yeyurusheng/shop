<?php

namespace App\Http\Controllers\Exam;

use App\Model\ExamLoginModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class LoginStatusController extends Controller
{
    //登录状态视图
    public function status(){
        $list = ExamLoginModel::all();
        $data = [
            'list' => $list
        ];
        return view('exam.status',$data);
    }
}
