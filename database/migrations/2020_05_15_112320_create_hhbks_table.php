<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHhbksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hhbks', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('city')->nullable();
            $table->string('product_name');
            $table->integer('company_id')->index()->nullable();
            $table->text('product_description')->nullable();
            $table->text('product_detail')->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE `hhbks` CHANGE `company_id` `company_id` INT(8) NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hhbks');
    }
}
