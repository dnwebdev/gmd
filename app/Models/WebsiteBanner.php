<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteBanner extends Model
{
    protected $table='tbl_website_banner';
    protected $fillable=['link','image','status','description','id_company'];

    public function company(){
    	return $this->hasOne('\App\Models\Company','id_company','id_company');
    }

    public static function list_status(){
    	return [1=>'Active',0=>'Not Active'];
    }

    public function getStatusTextAttribute(){
    	if($this->attributes['status'] == 1){
    		return 'Active';
    	}
    	else{
    		return 'Not Active';
    	}
    }
}
