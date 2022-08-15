<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class CustomerManualTransfer extends Model
{
    protected $table = 'customer_manual_transfer';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }
}
