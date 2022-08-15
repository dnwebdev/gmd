<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflineOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('productable_id')->nullable();
            $table->string('productable_type')->nullable();
            $table->integer('company_id')->index()->nullable();
            $table->string('product_name');
            $table->double('amount');
            $table->string('channel')->nullable();
            $table->string('client')->default('gomodo');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE `offline_orders` CHANGE `company_id` `company_id` INT(8) NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offline_orders');
    }
}
