<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeFinanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_finance', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_finance_id')->unsigned();
            $table->integer('duration_time')->default(0);;
            $table->string('name_time_id');
            $table->string('name_time_en');
            $table->timestamps();

            $table->foreign('category_finance_id')->references('id')->on('category_finance')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time_finance');
    }
}
