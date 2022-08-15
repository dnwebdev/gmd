<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    protected $table='tbl_product_pricing';
    protected $primaryKey = 'id_price';
    protected $fillable = ['id_product','currency','price','price_type','people', 'price_from', 'price_until'];
    public $timestamps = false;

    public function product()
    {
    	return $this->hasOne('\App\Models\Product','id_product','id_product');
    }

    public function unit()
    {
        return $this->hasOne(UnitName::class, 'id', 'unit_name_id');
    }

}
