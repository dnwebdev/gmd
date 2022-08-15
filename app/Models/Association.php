<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Association extends Model
{
    protected $table = 'tbl_association';
    protected $guarded = [];

    public function companies()
    {
        return $this->belongsToMany(Company::class,'tbl_company_association','id_association','id_company');
    }
}
