<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $table = "tbl_product_type";
    public $timestamps = false;
    protected $guarded = [];

    public function getProductTypeNameAttribute($value)
    {
        if (app()->getLocale() == 'id') {
            return $this->product_type_name_id;
        }

        return $value;
    }

    public function product_category()
    {
        return $this->belongsTo('App\Models\ProductCategory');
    }

    
}
