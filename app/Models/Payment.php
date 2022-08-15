<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table="tbl_payment";
    protected $primaryKey = "id_payment";
    protected $guarded=['id_payment'];
    protected $casts = [
      'response'=>'array',
      'response_midtrans'=>'array'
    ];

    public function order(){
    	return $this->belongsTo('\App\Models\Order','invoice_no','invoice_no');
    }

    public function kredivo()
    {
        return $this->hasOne(PaymentKredivo::class, 'payment_id', 'id_payment');
    }
}
