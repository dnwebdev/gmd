<?php

use Illuminate\Database\Seeder;
use App\Models\CategoryPayment;
use App\Models\Company;

class KredivoPaymentListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = CategoryPayment::where('name_third_party', 'Xendit Payment')
            ->first();

        if (!$category->listPayments()->where('code_payment', 'kredivo')->exists()) {
            $payment = $category->listPayments()
                ->create([
                    'name_payment' => 'Kredivo',
                    'name_payment_eng' => 'Kredivo',
                    'code_payment' => 'kredivo',
                    'status' => 0,
                    'image_payment' => 'img/xendit.png',
                    'type' => 'fixed',
                    'pricing_primary' => 0,
                    'type_secondary' => 'fixed',
                    'pricing_secondary' => 0,
                    'settlement_duration' => 0
                ]);

            $this->command->getOutput()->progressStart(Company::count());
            foreach (Company::get() as $company) {
                $company->payments()->attach($payment->id);
                $this->command->getOutput()->progressAdvance();
            }
            $this->command->getOutput()->progressFinish();
        }
    }
}
