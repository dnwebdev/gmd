<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManualTransfer extends Model
{
    protected $table = 'manual_transfer';
    protected $guarded = ['id'];

    public function company(){
    	return $this->belongsTo(Company::class,'company_id');
    }
    
}
