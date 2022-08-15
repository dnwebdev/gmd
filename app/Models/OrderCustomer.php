<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCustomer extends Model
{
    protected $table = 'tbl_order_customer';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';

    public function order(){
    	return $this->belongsTo('\App\Models\Order','invoice_no','invoice_no');
    }

    public function city(){
    	return $this->hasOne('\App\Models\City','id_city','id_city');
    }
}
