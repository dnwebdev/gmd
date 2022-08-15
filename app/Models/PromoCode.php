<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class PromoCode extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'tbl_promo_code';
    public $incrementing = false;
    use SoftDeletes;
    protected $dates = ['created_at','deleted_at','updated_at'];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }
}
