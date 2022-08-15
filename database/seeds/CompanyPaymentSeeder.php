<?php

use App\Models\Company;
use App\Models\ListPayment;
use Illuminate\Database\Seeder;

class CompanyPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (ListPayment::all() as $item) {
            $item->companies()->sync(Company::all());
            foreach ($item->companies as $data) {
                if (in_array($item->code_payment, ['credit_card','dana','linkaja','indomaret','gopay','bca_va','ovo_live','akulaku'])) {
                    $item->companies()->updateExistingPivot($data->id_company, ['charge_to' => 1]);
                }
            }
        }
    }
}
