<?php

use App\Models\CompanyPayment;
use App\Models\ListPayment;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePaymentCharge extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $payments = [
            'virtual_account' => [
                // primary
                'type' => 'fixed',
                'pricing_primary' => 4500,

                // secondary
                'type_secondary' => 'fixed',
                'pricing_secondary' => 0,
            ],
            'alfamart' => [
                // primary
                'type' => 'fixed',
                'pricing_primary' => 5000,

                // secondary
                'type_secondary' => 'fixed',
                'pricing_secondary' => 0,
            ],
            'alfamart_midtrans' => [
                // primary
                'type' => 'fixed',
                'pricing_primary' => 5000,

                // secondary
                'type_secondary' => 'fixed',
                'pricing_secondary' => 0,
            ],
            'indomaret' => [
                // primary
                'type' => 'fixed',
                'pricing_primary' => 5000,

                // secondary
                'type_secondary' => 'fixed',
                'pricing_secondary' => 0,
            ],
            'credit_card' => [
                // primary
                'type' => 'percentage',
                'pricing_primary' => 2.9,

                // secondary
                'type_secondary' => 'fixed',
                'pricing_secondary' => 2000,
            ],
            'ovo' => [
                // primary
                'type' => 'percentage',
                'pricing_primary' => 1.5,

                // secondary
                'type_secondary' => 'fixed',
                'pricing_secondary' => 0,
            ],
            'ovo_live' => [
                // primary
                'type' => 'percentage',
                'pricing_primary' => 1.5,

                // secondary
                'type_secondary' => 'fixed',
                'pricing_secondary' => 0,
            ],
            'linkaja' => [
                // primary
                'type' => 'percentage',
                'pricing_primary' => 1.5,

                // secondary
                'type_secondary' => 'fixed',
                'pricing_secondary' => 0,
            ],
            'dana' => [
                // primary
                'type' => 'percentage',
                'pricing_primary' => 1.5,

                // secondary
                'type_secondary' => 'fixed',
                'pricing_secondary' => 0,
            ],
            'kredivo' => [
                // primary
                'type' => 'percentage',
                'pricing_primary' => 2.3,

                // secondary
                'type_secondary' => 'fixed',
                'pricing_secondary' => 0,
            ],
            'akulaku' => [
                // primary
                'type' => 'percentage',
                'pricing_primary' => 2,

                // secondary
                'type_secondary' => 'fixed',
                'pricing_secondary' => 0,
            ]
        ];

        foreach ($payments as $code => $fee) {
            ListPayment::where('code_payment', $code)
                ->update($fee);
        }

        // Update semua company payment ke charge customer
        CompanyPayment::query()->update(['charge_to' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
