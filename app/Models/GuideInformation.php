<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class GuideInformation extends Model
{
    protected $guarded = [];
    public $incrementing = false;
    protected $table = 'tbl_guide_information';

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
}
