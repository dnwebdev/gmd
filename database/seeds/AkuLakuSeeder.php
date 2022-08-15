<?php

use App\Models\CategoryPayment;
use App\Models\Company;
use Illuminate\Database\Seeder;

class AkuLakuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = CategoryPayment::where('name_third_party', 'Midtrans Payment')
            ->first();

        if (!$category->listPayments()->where('code_payment', 'akulaku')->exists()) {
            $payment = $category->listPayments()
                ->create([
                    'name_payment' => 'AkuLaku',
                    'name_payment_eng' => 'AkuLaku',
                    'code_payment' => 'akulaku',
                    'status' => 0,
                    'image_payment' => 'img/static/payment/akulaku.png',
                    'type' => 'percentage',
                    'pricing_primary' => 1.7,
                    'type_secondary' => 'fixed',
                    'pricing_secondary' => 0,
                    'settlement_duration' => 5
                ]);

            $this->command->getOutput()->progressStart(Company::count());
            foreach (Company::get() as $company) {
                $company->payments()->attach($payment->id);
                $company->payments()->updateExistingPivot($payment->id, ['charge_to' => 1]);
                $this->command->getOutput()->progressAdvance();
            }
            $this->command->getOutput()->progressFinish();
        }
    }
}
