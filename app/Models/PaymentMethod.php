<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table = 'tbl_payment_method';
    protected $fillable = ['payment_method_name'];
}
