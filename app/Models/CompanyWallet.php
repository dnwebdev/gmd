<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyWallet extends Model
{
    protected $table = 'tbl_company_wallet';
    protected $guarded = ['id'];

    public function company(){
    	return $this->belongsTo('App\Models\Company','id_company','id_company');
    }
}
