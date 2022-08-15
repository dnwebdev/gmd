<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $table='tbl_customer_address';
    protected $primaryKey = 'id_address';
    protected $guarded = [];

    public function customer(){
    	return $this->hasOne('App\Models\Customer','id_customer','id_customer');
    }

    public function city(){
    	return $this->hasOne('\App\Models\City','id_city','id_city');
    }
}
