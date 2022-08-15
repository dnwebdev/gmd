<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class VerificationFinance extends Model
{
    protected $table = 'verification_finance';
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
