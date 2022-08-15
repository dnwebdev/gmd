<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRedeemPaymentlistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_list', function (Blueprint $table) {
            \Artisan::call('dump-autoload');
            \Artisan::call('db:seed', ['--class' => 'AddListRedeemVoucher', '--force' => true]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_list', function (Blueprint $table) {
            //
        });
    }
}
