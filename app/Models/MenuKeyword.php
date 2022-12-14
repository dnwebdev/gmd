<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class MenuKeyword extends Model
{
    protected $guarded = [];
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        self::creating(function ($model) {
            if (empty($model->id))
                $model->id = setOrderedUUID($model);
        });
    }

    public function menu()
    {
        return $this->belongsTo(MenuBot::class,'menu_id');
    }
}
