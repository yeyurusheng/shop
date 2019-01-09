<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CartModel extends Model{
    public $table = 'cart';
    public $timestamps = false;
    public function goodsInfo($g_id){
        return GoodsModel::where(['g_id'=>$g_id])->get();
    }
}
