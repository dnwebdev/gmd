<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class AdditionalOrder extends Model
{
    protected $guarded = [];
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class,'invoice_no');
    }

    public function insurance_details()
    {
        return $this->hasMany(InsuranceDetail::class,'additional_order_id');
    }

}
