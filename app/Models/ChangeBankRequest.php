<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class ChangeBankRequest extends Model
{
    protected $guarded = [];
    public $incrementing = false;
    protected $table = 'change_bank_account_requests';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }

    public function creator()
    {
        return $this->belongsTo(UserAgent::class,'id_user','id_user_agen');
    }

    public function bank_account()
    {
        return $this->belongsTo(CompanyBank::class,'id_company_bank');
    }

    public function owner()
    {
        return $this->bank_account->company->agent;
    }
}
