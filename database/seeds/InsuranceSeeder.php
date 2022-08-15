<?php

use Illuminate\Database\Seeder;

class InsuranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('insurances')->truncate();
        DB::table('insurance_pricings')->truncate();
        DB::table('product_insurance')->truncate();
        DB::table('insurance_forms')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        File::deleteDirectory(storage_path('app/public/uploads/insurance'));
        File::makeDirectory(storage_path('app/public/uploads/insurance'),0777,true,true);


        $jagadiri = new \App\Models\Insurance();
        $jagadiri->insurance_name_id = 'Jaga diri';
        $jagadiri->insurance_name_en = 'Jaga diri';
        $jagadiri->insurance_description_id = 'Jaga diri asuransi yang memberikan perlindungan jiwa bagi nasabah atas resiko kecelakaan, termasuk perlindungan pada olahraga dan pekerjaan yang beresiko tinggi berupa santunan meninggal dunia dan santunan perawatan rumah sakit karena kecelakaan dengan berbagai pilihan jangka waktu perlindungan yang disesuaikan dengan kebutuhan nasabah.';
        $jagadiri->insurance_description_en = 'Jaga diri insurance that provides life protection for customers against accident risks, including protection for sports and high-risk occupations in the form of death benefits and compensation for hospital care due to accidents with various choices of protection periods that are tailored to the needs of customers.';
        $jagadiri->insurance_slug = 'jaga-diri';
        $jagadiri->status = false;
        $jagadiri->insurance_logo = Storage::disk('public')->put('uploads/insurance/jagadiri.png',File::get(public_path('/img/Jagadiri_hdpi.png')))?'storage/uploads/insurance/jagadiri.png':null;
        $jagadiri->save();

        $pricings = [
            [
                'day' => 1,
                'price' => 7000,
                'display_id' => '1 hari',
                'display_en' => '1 day'
            ],
            [
                'day' => 2,
                'price' => 7500,
                'display_id' => '2 hari',
                'display_en' => '2 days'
            ],
            [
                'day' => 7,
                'price' => 9000,
                'display_id' => '1 minggu',
                'display_en' => '1 week'
            ],
            [
                'day' => 14,
                'price' => 11500,
                'display_id' => '2 minggu',
                'display_en' => '2 weeks'
            ],
            [
                'day' => 30,
                'price' => 13500,
                'display_id' => '1 bulan',
                'display_en' => '1 month'
            ],
            [
                'day' => 90,
                'price' => 30500,
                'display_id' => '3 bulan',
                'display_en' => '3 months'
            ],
            [
                'day' => 150,
                'price' => 47000,
                'display_id' => '5 bulan',
                'display_en' => '5 months'
            ],
            [
                'day' => 365,
                'price' => 67000,
                'display_id' => '1 tahun',
                'display_en' => '1 year'
            ],
        ];
        $jagadiri = \App\Models\Insurance::where('insurance_slug', 'jaga-diri')->first();
        foreach ($pricings as $pricing):
            $pricing['insurance_id'] = $jagadiri->id;
            \App\Models\InsurancePricing::create($pricing);
        endforeach;
        $forms = [
            [
                'insurance_id' => $jagadiri->id,
                'name' => 'insurance_customer_name',
                'label_id' => 'Nama',
                'label_en' => 'Name',
                'class'=>'form-control',
                'type' => 'text',
                'options' => null,
                'required' => true,
                'purpose' => 'customer'
            ],
            [
                'insurance_id' => $jagadiri->id,
                'name' => 'insurance_customer_phone',
                'label_id' => 'Nomor telpon',
                'label_en' => 'Phone number',
                'class'=>'form-control phone number',
                'type' => 'phone',
                'options' => null,
                'required' => true,
                'purpose' => 'customer'
            ],
            [
                'insurance_id' => $jagadiri->id,
                'name' => 'insurance_customer_dob',
                'label_id' => 'Tanggal lahir',
                'label_en' => 'Birthday',
                'type' => 'date',
                'class'=>'form-control datepicker',
                'options' => null,
                'required' => true,
                'purpose' => 'customer'
            ],
            [
                'insurance_id' => $jagadiri->id,
                'name' => 'insurance_customer_identity_number',
                'label_id' => 'Nomor KTP',
                'label_en' => 'ID Card Number',
                'class'=>'form-control phone number',
                'type' => 'ktp',
                'options' => null,
                'required' => true,
                'purpose' => 'customer'
            ],
            [
                'insurance_id' => $jagadiri->id,
                'name' => 'insurance_customer_address',
                'label_id' => 'Alamat',
                'label_en' => 'Address',
                'type' => 'textarea',
                'class'=>'form-control md-textarea',
                'options' => null,
                'required' => true,
                'purpose' => 'customer'
            ],
            [
                'insurance_id' => $jagadiri->id,
                'name' => 'insurance_participant_name',
                'label_id' => 'Nama',
                'label_en' => 'Name',
                'class'=>'form-control',
                'type' => 'text',
                'options' => null,
                'required' => true,
                'purpose' => 'participants'
            ],
            [
                'insurance_id' => $jagadiri->id,
                'name' => 'insurance_participant_dob',
                'label_id' => 'Tanggal lahir',
                'label_en' => 'Birthday',
                'class'=>'form-control datepicker',
                'type' => 'date',
                'options' => null,
                'required' => true,
                'purpose' => 'participants'
            ],
        ];

        foreach ($forms as $form):
            \App\Models\InsuranceForm::create($form);
        endforeach;
    }
}
