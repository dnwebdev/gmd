<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class CompanyUpdate extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ( $model ) {
            $model->id = Uuid::uuid4()->toString();
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'id_company');
    }

    public function up_date()
    {
        return $this->belongsTo(Update::class, 'id_update');
    }
}
