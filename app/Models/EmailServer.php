<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailServer extends Model
{
    protected $table = 'tbl_company_email_server';
    protected $guarded = ['id'];

    public function company(){
    	return $this->belongsTo('App\Models\Company','id_company','id_company');
    }

    public static function list_status(){
        
        return [true=>'Active'
                ,false=>'In Active'
                ];
    }
}
