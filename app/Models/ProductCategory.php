<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $primaryKey = 'id_category';
    protected $table = "tbl_product_category";
    protected $fillable = ['id_company','category_name','id_product_type','created_by'];

    public function product_type()
    {
        return $this->hasOne('App\Models\ProductType','id_tipe_product','id_product_type');
    }

    public function product(){
        return $this->belongsTo('\App\Models\Product','id_product','id_product');
    }

    public static function list_status(){
    	return ['1'=>'Active',0=>'Not Active'];
    }
}
