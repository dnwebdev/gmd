<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListPayment extends Model
{
    protected $table = 'payment_list';
    protected $guarded = [];

    public function categoryPayment()
    {
        return $this->belongsTo(CategoryPayment::class, 'category_payment_id');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class,'company_payment','payment_id','company_id')->withPivot(['charge_to']);
    }


}
