<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionRequest extends Model
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
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
