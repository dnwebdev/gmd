<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawRequest extends Model
{
    protected $table = 'tbl_company_withdraw_request';
    protected $primaryKey = 'document_no';
    public $incrementing = false;
    protected $guarded = [];
    protected $appends=['amount_text'];

    public static function list_status(){
    	return [0=>\trans('general.status.pending'),1=>\trans('general.status.completed'),2=>\trans('general.status.failed')]; 
    }

    public function getStatusTextAttribute(){
        $value = $this->attributes['status'];
        
        $list_status = $this->list_status();
        
        return $list_status[$value];
    }
    public function getAmountTextAttribute(){
        $value = $this->attributes['amount'];

        return format_priceID($value,$this->attributes['currency']);
    }
    public function company()
    {
        return $this->belongsTo(Company::class,'id_company');
    }
}
