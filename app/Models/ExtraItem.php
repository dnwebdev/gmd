<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtraItem extends Model
{
    protected $table ='tbl_extra';
    protected $primaryKey = 'id_extra';
    protected $guarded = ['id_extra'];

    public function extra_order(){
        return $this->belongsTo(OrderExtraItem::class,'id_extra','id_extra');
    }


    public static function list_price_type(){
    	return [0=>'Per Order',1=>'Per Quantity'];
    }

    public static function list_currency(){
    	return ['IDR'=>'IDR'];
    }

    public function getPriceTypeTextAttribute(){
    	if($this->attributes['extra_price_type'] == 1){
    		return 'Per Quantity';
    	}
    	else{
    		return 'Per Order';
    	}
    }


}
