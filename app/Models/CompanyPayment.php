<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyPayment extends Model
{
    protected $table = 'company_payment';
    protected $guarded = [];
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function paymentLists()
    {
        return $this->belongsTo(ListPayment::class, 'payment_id');
    }
}
