<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyToken extends Model
{
    protected $table='tbl_company_token';
    protected $primaryKey='id_token';
    protected $fillable = ['id_company','token','expired_at'];
    public $timestamps = false;


    public function company(){
    	return $this->belongsTo(Company::class,'id_company');
    }
}
