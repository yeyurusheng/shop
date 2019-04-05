<?php

namespace App\Http\Controllers\Secret;

use App\Model\SecretModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class SecretController extends Controller
{
    //前台申请页面
    public function apply(){
        return view('secret.apply');
    }

    public function doapply(Request $request){
        $name = $request->input('u_name');
        $card_id = $request->input('card_id');
        $photos = $request->input('photos');
        $purpose = $request->input('purpose');
        $data=[
            'name' =>$name,
            'card_id' => $card_id,
            'photos' => $photos,
            'purpose' => $purpose,
            'status' => 0,
        ];
        SecretModel::insert($data);
    }

    //后台审核
    public function examine(){
        $list = SecretModel::all();
        $data = [
            'list' => $list
        ];
        return view('secret.examine',$data);
    }
}
