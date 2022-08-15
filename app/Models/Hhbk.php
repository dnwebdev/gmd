<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hhbk extends Model
{
    protected $guarded = [];
    public $incrementing = false;

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
