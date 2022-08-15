<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagProduct extends Model
{
    protected $table = "tbl_tag_products";
    protected $guarded = [];

    public function products()
    {
        return $this->belongsToMany(Product::class,'tbl_tag_products_value','tag_product_id','product_id');
    }

}
