<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderExtraItem extends Model
{
    protected $table = 'tbl_order_extra';
    protected $guarded = ['id'];

    public function order(){
    	return $this->belongsTo('\App\Models\Order','invoice_no','invoice_no');
    }

    public function extra_item(){
    	return $this->hasOne('\App\Models\ExtraItem','id_extra','id_extra');
    }
}
