<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryPayment extends Model
{
    protected $table = 'category_payment';
    protected $guarded = [];

    public function listPayments()
    {
        return $this->hasMany(ListPayment::class, 'category_payment_id');
    }
}
