<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(CityInEnglishSeeder::class);
        $this->call(CategoryPaymentSeeder::class);
        $this->call(PaymentListSeeder::class);
        $this->call(CompanyPaymentSeeder::class);
    }
}
