<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table='tbl_customer';
    protected $primaryKey = 'id_customer';
    protected $guarded = ['id_address'];

    public function company(){
    	return $this->hasOne('App\Models\Company','id_company','id_company');
    }

    public function city()
    {
        return $this->belongsTo(City::class,'id_city');
    }

    public function getCreatedAtAttribute($value){
    	return date('M d, Y h:i A',strtotime($value));
    }

    public function getStatusAttribute($value){
    	if($value == 1){
    		return 'Active';
    	}
    	elseif($value == 2){
    		return 'Banned';
    	}
    	else{
    		return 'Not Active';
    	}
    }

    public function address(){
        return $this->hasMany('\App\Models\CustomerAddress','id_customer','id_customer');
    }

    public function getMainAddressAttribute(){
        $addr = $this->address()->where('is_primary',1)->first();
        return $addr;
    }

}
