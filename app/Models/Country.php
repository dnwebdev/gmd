<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'tbl_country';
    protected $primaryKey = 'id_country';

    public function state(){
    	return $this->hasMany('\App\Models\State','id_country','id_country');
    }
}
