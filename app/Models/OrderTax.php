<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTax extends Model
{
    protected $table = 'tbl_order_tax';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';

    public function order_detail(){
    	return $this->belongsTo('\App\Models\OrderDetail','id','order_detail_id');
    }

    public function tax(){
    	return $this->hasMany('\App\Models\Tax','id_tax','id_tax');
    }
}
