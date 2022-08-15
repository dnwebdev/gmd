<?php

use App\Models\CategoryPayment;
use App\Models\Company;
use Illuminate\Database\Seeder;

class OvoLiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = CategoryPayment::where('name_third_party', 'Xendit Payment')->first();
        
        if ($category->listPayments()->where('code_payment', 'ovo_live')->exists()) {
            $payment = $category->listPayments()->where('code_payment', 'ovo_live')->first();
            $payment->update([
                'type' => 'percentage',
                'pricing_primary' => 1.5,
            ]);
            $this->command->getOutput()->progressStart(Company::count());
            foreach (Company::get() as $company) {
                $company->payments()->updateExistingPivot($payment->id, ['charge_to' => 1]);
                $this->command->getOutput()->progressAdvance();
            }
            $this->command->getOutput()->progressFinish();
            
        } elseif(!$category->listPayments()->where('code_payment', 'ovo_live')->exists()) {
            $payment = $category->listPayments()
                ->create([
                    'name_payment' => 'OVO',
                    'name_payment_eng' => 'OVO',
                    'code_payment' => 'ovo_live',
                    'status' => 0,
                    'image_payment' => 'img/static/payment/ovo.png',
                    'type' => 'percentage',
                    'pricing_primary' => 1.5,
                    'type_secondary' => 'fixed',
                    'pricing_secondary' => 0,
                    'settlement_duration' => 2
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
