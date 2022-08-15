<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTax extends Model
{
    protected $table='tbl_product_tax';
    protected $fillable = ['id_product','id_tax'];

    public function product(){
    	return $this->belongsTo('\App\Models\Product','id_product','id_product');
    }

    public function tax(){
    	return $this->belongsTo('\App\Models\Tax','id_tax','id_tax');
    }
}
