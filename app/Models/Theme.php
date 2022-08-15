<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $table = 'tbl_theme';
    protected $primaryKey = 'id_theme';

    public function company_theme(){
    	return $this->hasMany('\App\Models\CompanyTheme','id_theme','id_theme');
    }
}
