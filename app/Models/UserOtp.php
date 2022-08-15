<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class UserOtp extends Model
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

    public function user()
    {
        return $this->belongsTo(UserAgent::class,'user_id','id_user_agen');
    }
}
