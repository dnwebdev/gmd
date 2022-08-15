<?php

namespace App\Models;

use App\Scopes\OnlineProductScope;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table='tbl_order_detail';
    protected $guarded = ['id'];

    public function invoice()
    {
        return $this->belongsTo('App\Models\Order','invoice_no','invoice_no');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product','id_product','id_product')->withTrashed()->withoutGlobalScope(OnlineProductScope::class);
    }

    public function tax(){
    	return $this->hasMany('\App\Models\OrderTax','order_detail_id','id');
    }

    public function getMainImageSrcAttribute(){
        $value = $this->attributes['main_image'];
        return asset('uploads/orders/'.$value);
    }

    public function getScheduleDateFormatedAttribute(){
        $value = $this->attributes['schedule_date'];
        return date('d M, Y',strtotime($value));
    }

    public function city(){
        return $this->hasOne('\App\Models\City','id_city','id_city');
    }

    public function category(){
        return $this->hasOne('\App\Models\ProductCategory','id_category','id_product_category');
    }

    public function product_type(){
        return $this->hasOne('\App\Models\ProductType','id_tipe_product','id_product_type');
    }

    public function getDurationTypeTextAttribute(){
        $value = $this->attributes['duration_type'];

        switch ($value) {
            case 0:
                return 'Hours';
                break;
            
            default:
                return 'Days';
                break;
        }
    }

    public function customDetail()
    {
        return $this->hasMany(CustomDetail::class, 'order_detail_id');
    }

    public function insurance_pricing()
    {
        return $this->belongsTo(InsurancePricing::class,'insurance_pricing_id');
    }

    public function unit()
    {
        return $this->hasOne(UnitName::class, 'id', 'unit_name_id');
    }
}
