<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_list', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_payment_id')->unsigned();
            $table->string('name_payment', 255);
            $table->string('name_payment_eng', 255);
            $table->string('code_payment', 255);
            $table->tinyInteger('status')->nullable()->default(0);
            $table->string('image_payment', 255);
            $table->enum('type', ['percentage','fixed'])->default('fixed');
            $table->enum('type_secondary', ['percentage','fixed'])->default('fixed');
            $table->double('pricing_primary')->default(0);
            $table->double('pricing_secondary')->default(0);
            $table->integer('settlement_duration')->default(0);
            $table->timestamps();

            $table->foreign('category_payment_id')->references('id')->on('category_payment')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_list');
    }
}
