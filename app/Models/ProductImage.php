<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table='tbl_product_image';
    protected $fillable = ['id_product','url','is_primary'];
    protected $primaryKey = 'id_image';
    public function product(){
    	return $this->hasOne('\App\Models\Product','id_product','id_product');
    }

    public function getImageSrcAttribute(){
        $value = $this->attributes['url'];
        if (\File::exists('uploads/products/'.$value)){
            return asset('uploads/products/'.$value);
        }
        return asset('img/no-product-image.png');
    }
    
}
