<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagProductValue extends Model
{
    protected $table = "tbl_tag_products_value";

    public function tag()
    {
    	return $this->hasOne(TagProduct::class, 'id','tag_product_id');
    }
}
