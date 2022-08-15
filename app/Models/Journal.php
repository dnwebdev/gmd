<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $table='tbl_journal';
    protected $guarded = ['id'];

    public function company(){
    	return $this->hasOne('\App\Models\Company','id_company','id_company');
    }

    public static function list_status(){
    	return [0=>'Not Processed',1=>'Active',2=>'Void'];
    }

    public function getStatusTextAttribute(){
        $value = $this->attributes['status'];
        
        $list_status = $this->list_status();
        
        return $list_status[$value];
    }
}
