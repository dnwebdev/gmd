<?php

use Illuminate\Database\Seeder;

class AddListRedeemVoucher extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = \App\Models\CategoryPayment::where('name_third_party', 'Xendit Payment')->first();
        $list = new \App\Models\ListPayment();
        $list->category_payment_id = $category->id;
        $list->code_payment = 'redeem';
        $list->name_payment = 'Menukarkan Voucher';
        $list->name_payment_eng = 'Redeem Voucher';
        $list->status = 0;
        $list->image_payment = 'img/static/payment/redeem-voucher.png';
        $list->type = 'fixed';
        $list->type_secondary = 'fixed';
        $list->pricing_primary = 0;
        $list->pricing_secondary = 0;
        $list->settlement_duration = 0;
        $list->save();

        $list->companies()->sync(\App\Models\Company::all());
        foreach ($list->companies as $data) {
            $list->companies()->updateExistingPivot($data->id_company, ['charge_to' => 1]);
        }
        $list->save();
    }
}
