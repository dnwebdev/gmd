<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDanaCompanypaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_payment', function (Blueprint $table) {
            \Artisan::call('dump-autoload');
            \Artisan::call('db:seed', ['--class' => 'CompanyPaymentSeeder', '--force' => true]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_payment', function (Blueprint $table) {
            //
        });
    }
}
