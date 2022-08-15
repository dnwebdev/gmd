<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Update extends Model
{
    protected $guarded = [];
    public $incrementing = false;
    protected $table = 'tbl_updates';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class,'update_by');
    }

    public function companies(  )
    {
        return $this->belongsToMany(Company::class,'company_update','id_update','id_company');
    }
}
