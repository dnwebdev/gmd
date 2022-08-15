<?php

use Illuminate\Database\Seeder;

class BusinessCategoryIndonesia extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('UPDATE tbl_business_category SET business_category_name_id = business_category_name');
    }
}
