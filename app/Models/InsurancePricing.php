<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsurancePricing extends Model
{
    protected $guarded = [];

    public function insurance()
    {
        return $this->belongsTo(Insurance::class,'insurance_id');
    }

    public function order_details()
    {
        return $this->hasMany(OrderDetail::class,'insurance_pricing_id');
    }
}
