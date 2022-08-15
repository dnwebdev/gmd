<?php

namespace App\Models;

use App\Scopes\ActiveProviderScope;
use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    protected $table = 'tbl_kyc';
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model){
            \File::deleteDirectory(public_path('storage/uploads/kyc/'.$model->id_company));
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class,'id_company')->withoutGlobalScope(ActiveProviderScope::class);
    }
}
