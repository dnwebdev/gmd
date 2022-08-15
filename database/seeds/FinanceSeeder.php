<?php


use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;

class FinanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        \DB::table('category_finance')->truncate();
        \DB::table('time_finance')->truncate();
        \DB::table('type_finance')->truncate();
        Schema::enableForeignKeyConstraints();
        //\DB::statement('SET FOREIGN_KEY_CHECKS=1=;');
        \File::deleteDirectory(storage_path('app/public/uploads/finance'));
        \File::makeDirectory(storage_path('app/public/uploads/finance'),0777,true,true);

        $category = new \App\Models\CategoryFinance();
        $category->name_finance = 'koinworks';
        $category->save();

        $category = \App\Models\CategoryFinance::where('name_finance', 'koinworks')->first();
        $time_finance = [
            [
                'category_finance_id' => $category->id,
                'duration_time' => 1,
                'name_time_id' => '1 Bulan',
                'name_time_en' => '1 Month',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_finance_id' => $category->id,
                'duration_time' => 2,
                'name_time_id' => '2 Bulan',
                'name_time_en' => '2 Month',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_finance_id' => $category->id,
                'duration_time' => 3,
                'name_time_id' => '3 Bulan',
                'name_time_en' => '3 Month',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        \DB::table('time_finance')->insert($time_finance);

        $type_finance = [
            [
                'category_finance_id' => $category->id,
                'title_id' => 'Pinjaman Dana',
                'title_en' => 'Loan Funds',
                'content_id' => 'Ajukan pinjaman dana minimal sebesar Rp 10.000.000 untuk menjadi tambahan modal bagi perkembangan bisnis anda.',
                'content_en' => 'Request loan funds with the minimum of Rp 10.000.000 for additional funds to grow your business ever further.',
                'button_id' => 'Ajukan Peminjaman',
                'button_en' => 'Request Loan',
                'image' => 'img/finance/80-px-ajukan-pinjaman-dana.png',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_finance_id' => $category->id,
                'title_id' => 'Dana Hibah',
                'title_en' => 'Grant Funds',
                'content_id' => 'Fitur Dana Hibah segera hadir di Gomodo!',
                'content_en' => 'Grant Funds feature will be arriving in Gomodo!',
                'button_id' => 'Segera Hadir',
                'button_en' => 'Coming Soon',
                'image' => 'img/finance/80-px-dana-hibah.png',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        \DB::table('type_finance')->insert($type_finance);
    }
}
