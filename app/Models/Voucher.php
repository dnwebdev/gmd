<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = "tbl_voucher";
    protected $guarded = ['id_voucher'];
    protected $primaryKey = 'id_voucher';
    
    public function company(){
    	return $this->hasOne('App\Models\Company','id_company','id_company');
    }

    public function product_type(){
    	return $this->hasOne('App\Models\ProductType','id_tipe_product','id_product_type');
    }

    public function customer(){
    	return $this->hasOne('App\Models\Customer','id_customer','id_customer');
    }

    public function order(){
        return $this->hasMany('\App\Models\Order','id_voucher','id_voucher');
    }
    public function order_paid(){
        return $this->hasMany('\App\Models\Order','id_voucher','id_voucher')->whereIn('status',[0,1,2,3,4]);
    }
    public function order_used_total(){
        return $this->hasMany('\App\Models\Order','id_voucher','id_voucher')->whereIn('status',[0,1,2,3,4]);
    }

    public function product(){
        return $this->hasOne('\App\Models\Product','id_product','id_product')->withTrashed();
    }

    public static function list_currency(){
        return ['IDR'=>'IDR'];
    }

    public function getStatusAttribute($value)
    {
        return ($value == 1 ? \trans('general.active') : \trans('general.not_active')); 
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'voucher_products', 'voucher_id', 'product_id');
    }
}
