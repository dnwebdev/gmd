<?php

use App\Models\CategoryPayment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CategoryPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'Midtrans Payment' => 'Midtrans Payment',
            'Xendit Payment' => 'Xendit Payment',
            'Cod' => 'Pembayaran Tunai',
        ];

        CategoryPayment::insert(
            collect($data)->map(function ($value, $key) {
                return [
                    'name_third_party' => $value,
                    'name_third_party_eng' => $key,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ];
            })->values()->toArray()
        );
    }
}
