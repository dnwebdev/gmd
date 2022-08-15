<?php

use App\Models\CategoryPayment;
use App\Models\Company;
use App\Models\ListPayment;
use App\Models\ManualTransfer;
use Illuminate\Database\Seeder;

class BcaPaymentlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            \DB::beginTransaction();
            $category = new CategoryPayment();
            $category->name_third_party = 'Transfer Manual';
            $category->name_third_party_eng = 'Manual Transfer';
            $category->save();
    
            $list = new ListPayment();
            $list->category_payment_id = $category->id;
            $list->name_payment = 'BCA Transfer Manual';
            $list->name_payment_eng = 'BCA Manual Transfer';
            $list->code_payment = 'bca_manual';
            $list->status = 0;
            $list->image_payment = 'img/static/payment/bca.png';
            $list->type = 'fixed';
            $list->type_secondary = 'fixed';
            $list->pricing_primary = 0;
            $list->pricing_secondary = 0;
            $list->settlement_duration = 0;
            $list->save();

            $list->companies()->sync(Company::all());
            \DB::commit();
        } catch (\Exception $e) {
            return [
                'oke' => false,
                'status' => $e->getMessage()
            ];
        }
    }
}
