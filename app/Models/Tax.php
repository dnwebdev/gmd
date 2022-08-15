<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $primaryKey = "id_tax";
    protected $table = "tbl_tax";
    protected $fillable = ['id_company','tax_name','tax_amount','tax_amount_type','status'];

    public static function list_status(){
    	return [1=>'Active',0=>'Not Active'];
    }

    public static function list_amount_type(){
        return [0=>'Fixed Amount',1=>'Percentage'];
    }

    public function product_tax(){
    	return $this->hasMany('\App\Models\ProductTax','id_tax','id_tax');
    }
    

}
