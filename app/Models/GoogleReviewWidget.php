<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class GoogleReviewWidget extends Model
{
    protected $guarded = [];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}
