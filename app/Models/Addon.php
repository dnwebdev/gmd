<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    protected $table = 'tbl_addon';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function products()
    {
        return $this->belongsToMany(Product::class,'tbl_join_product_add','id_add','id_product');
    }
}
