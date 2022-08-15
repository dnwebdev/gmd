<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypeFinanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_finance', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_finance_id')->unsigned();
            $table->string('title_id')->nullable();
            $table->string('title_en')->nullable();
            $table->longText('content_id');
            $table->longText('content_en');
            $table->string('button_id')->nullable();
            $table->string('button_en')->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('type_finance');
    }
}
