<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class CreatePaymentKredivoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_kredivo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payment_id')->length(11);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_number', 20);
            $table->string('email');
            $table->text('address');
            $table->integer('city_id')->length(11);
            $table->string('postal_code');
            $table->enum('installment_duration', ['30_days', '3_months', '6_months', '12_months']);
            $table->text('response')->nullable();
            $table->boolean('email_reminder_sent')->default(false);
            $table->timestamps();

            $table->foreign('payment_id')
                ->references('id_payment')
                ->on('tbl_payment')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('city_id')
                ->references('id_city')
                ->on('tbl_city')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Artisan::call('db:seed', ['--class' => 'KredivoPaymentListSeeder', '--force' => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_kredivo');
    }
}
