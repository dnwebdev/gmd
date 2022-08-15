<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductInsurance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_insurance', function (Blueprint $table) {
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('insurance_id');
        });
        DB::statement('ALTER TABLE `product_insurance` CHANGE `product_id` `product_id` INT(11) NOT NULL;');
        Schema::table('product_insurance', function (Blueprint $table) {
            $table->foreign('product_id')
                ->references('id_product')
                ->on('tbl_product')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('insurance_id')
                ->references('id')
                ->on('insurances')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->primary(['product_id','insurance_id']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_insurance');
    }
}
