<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_payment', function (Blueprint $table) {
//            $table->increments('id');
            $table->integer('company_id')->index();
            $table->integer('payment_id')->unsigned();
            $table->tinyInteger('charge_to')->default(0);

            $table->foreign('company_id')->references('id_company')->on('tbl_company')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payment_list')->onUpdate('cascade')->onDelete('cascade');
        });
        Artisan::call('db:seed', ['--class'=>'CategoryPaymentSeeder','--force'=>true]);
        Artisan::call('db:seed', ['--class'=>'PaymentListSeeder','--force'=>true]);
        Artisan::call('db:seed', ['--class'=>'CompanyPaymentSeeder','--force'=>true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_payment');
    }
}
