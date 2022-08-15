<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class RawInbox extends Model
{
    public $incrementing = false;
    protected $guarded = [];
    protected $casts = [
        'raw' => 'array'
    ];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        self::creating(function ($model) {
            if (empty($model->id))
                $model->id = setOrderedUUID($model);
        });
    }
}
