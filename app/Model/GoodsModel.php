<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodsModel extends Model
{
    public $table = 'goods';
    public $timestamps = false;
    public $primaryKey = 'g_id';

    //格式化某字段的值
    public function getGPriceAttribute($g_price){
        return $g_price / 100;
    }
}
