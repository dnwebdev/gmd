<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyNotification extends Model
{
    protected $table = 'tbl_company_notification';
    protected $guarded = ['id'];

    public function getCreatedAtAttribute(){
    	$val = $this->attributes['created_at'];
    	$utility = app('\App\Services\UtilityService');
        $formated = $utility->format_human_time(date('Y-m-d H:i:s'),$val,1);

        return $formated;
    }
}
