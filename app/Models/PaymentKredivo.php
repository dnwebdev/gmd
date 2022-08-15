<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentKredivo extends Model
{
    protected $guarded = [];

    protected $table = 'payment_kredivo';

    protected $casts = [
      'response'            => 'array',
      'reminder_email_sent' => 'boolean'
    ];

    public static $durations = [
        '30_days' => '30 {{Days}}',
        '3_months' => '3 {{Months}}',
        '6_months' => '6 {{Months}}',
        '12_months' => '12 {{Months}}'
    ];

    public function payment()
    {
        return $this->hasOne(Payment::class, 'id_payment', 'payment_id');
    }

    public function city()
    {
        return $this->hasOne(City::class, 'id_city', 'city_id');
    }
}
