<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $guarded = ['id_language'];
    protected $primaryKey = 'id_language';
    protected $table = 'tbl_language';
    public $timestamps = false;

    public function products()
    {
        return $this->belongsToMany(Product::class,'tbl_language_product','id_language','id_product');
    }
}
