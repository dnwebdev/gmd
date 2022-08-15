<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyTheme extends Model
{
    protected $table='tbl_company_theme';
    protected $guarded = [];

    public function company(){
    	return $this->belongsTo('App\Models\Company','id_company','id_company');
    }

    public function theme(){
    	return $this->belongsTo('\App\Models\Theme','id_theme','id_theme');
    }

    public static function list_status(){
        return [1=>'Active',0=>'Not Active'];
    }

}
