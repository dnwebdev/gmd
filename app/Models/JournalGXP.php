<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class JournalGXP extends Model
{
    protected $table='tbl_journal_gxp';
    protected $guarded = ['id'];
    public $incrementing = false;
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }
    public function company(){
    	return $this->hasOne('\App\Models\Company','id_company','id_company');
    }
}
