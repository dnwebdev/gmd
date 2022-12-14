<?php

use App\Models\ListPayment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PaymentListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'category_payment_id' => 2,
                'name_payment' => 'Bank Transfer',
                'name_payment_eng' => 'Bank Transfer',
                'code_payment' => 'virtual_account',
                'status' => 1,
                'image_payment' => 'img/static/payment/bank_transfer.png',
                'type' => 'fixed',
                'pricing_primary' => 0,
                'type_secondary' => 'fixed',
                'pricing_secondary' => 0,
                'settlement_duration' => 0,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'category_payment_id' => 2,
                'name_payment' => 'Alfamart',
                'name_payment_eng' => 'Alfamart',
                'code_payment' => 'alfamart',
                'status' => 1,
                'image_payment' => 'img/static/payment/alfamart.png',
                'type' => 'fixed',
                'pricing_primary' => 0,
                'type_secondary' => 'fixed',
                'pricing_secondary' => 0,
                'settlement_duration' => 0,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'category_payment_id' => 2,
                'name_payment' => 'Kartu Kredit',
                'name_payment_eng' => 'Credit Card',
                'code_payment' => 'credit_card',
                'status' => 1,
                'image_payment' => 'img/static/payment/credit_card.png',
                'type' => 'percentage',
                'pricing_primary' => 2.9,
                'type_secondary' => 'fixed',
                'pricing_secondary' => 0,
                'settlement_duration' => 0,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'category_payment_id' => 2,
                'name_payment' => 'OVO',
                'name_payment_eng' => 'OVO',
                'code_payment' => 'ovo',
                'status' => 1,
                'image_payment' => 'img/static/payment/ovo.png',
                'type' => 'percentage',
                'pricing_primary' => 1.5,
                'type_secondary' => 'fixed',
                'pricing_secondary' => 0,
                'settlement_duration' => 0,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'category_payment_id' => 2,
                'name_payment' => 'Dana',
                'name_payment_eng' => 'Dana',
                'code_payment' => 'dana',
                'status' => 0,
                'image_payment' => 'img/static/payment/dana.png',
                'type' => 'percentage',
                'pricing_primary' => 1.5,
                'type_secondary' => 'fixed',
                'pricing_secondary' => 0,
                'settlement_duration' => 2,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'category_payment_id' => 2,
                'name_payment' => 'LinkAja',
                'name_payment_eng' => 'LinkAja',
                'code_payment' => 'linkaja',
                'status' => 0,
                'image_payment' => 'img/static/payment/linkaja.png',
                'type' => 'percentage',
                'pricing_primary' => 1.5,
                'type_secondary' => 'fixed',
                'pricing_secondary' => 0,
                'settlement_duration' => 2,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'category_payment_id' => 3,
                'name_payment' => 'Pembayaran Tunai',
                'name_payment_eng' => 'Cash',
                'code_payment' => 'cod',
                'status' => 1,
                'image_payment' => 'img/static/payment/cod.png',
                'type' => 'fixed',
                'pricing_primary' => 0,
                'type_secondary' => 'fixed',
                'pricing_secondary' => 0,
                'settlement_duration' => 0,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'category_payment_id' => 1,
                'name_payment' => 'Indomaret',
                'name_payment_eng' => 'Indomaret',
                'code_payment' => 'indomaret',
                'status' => 0,
                'image_payment' => 'img/midtrans/indomaret.png',
                'type' => 'fixed',
                'pricing_primary' => 0,
                'type_secondary' => 'fixed',
                'pricing_secondary' => 1000,
                'settlement_duration' => 6,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'category_payment_id' => 1,
                'name_payment' => 'Gopay',
                'name_payment_eng' => 'Gopay',
                'code_payment' => 'gopay',
                'status' => 0,
                'image_payment' => 'img/static/payment/gopay.png',
                'type' => 'percentage',
                'pricing_primary' => 2,
                'type_secondary' => 'fixed',
                'pricing_secondary' => 0,
                'settlement_duration' => 3,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'category_payment_id' => 1,
                'name_payment' => 'Alfamart',
                'name_payment_eng' => 'Alfamart',
                'code_payment' => 'alfamart_midtrans',
                'status' => 0,
                'image_payment' => 'img/static/payment/alfamart.png',
                'type' => 'fixed',
                'pricing_primary' => 5000,
                'type_secondary' => 'fixed',
                'pricing_secondary' => 0,
                'settlement_duration' => 6,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'category_payment_id' => 1,
                'name_payment' => 'BCA',
                'name_payment_eng' => 'BCA',
                'code_payment' => 'bca_va',
                'status' => 0,
                'image_payment' => 'img/static/payment/bca.png',
                'type' => 'fixed',
                'pricing_primary' => 2750,
                'type_secondary' => 'fixed',
                'pricing_secondary' => 0,
                'settlement_duration' => 0,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]
        ];

        \DB::table('payment_list')->insert($data);
    }
}
