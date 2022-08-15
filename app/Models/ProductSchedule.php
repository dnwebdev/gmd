<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSchedule extends Model
{
    protected $table ='tbl_product_schedule';
    protected $primaryKey = 'id_schedule';

    protected $guarded = ['id_schedule'];

    public $timestamps = false;

    protected $dates = [
        'start_date',
        'end_date'
    ];

    public function product(){
    	return $this->hasOne('\App\Models\Product','id_product','id_product');
    }

}
