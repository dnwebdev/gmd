<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_keywords', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('menu_id')->index();
            $table->string('keyword');
            $table->timestamps();
            $table->foreign('menu_id')->references('id')->on('menu_bots')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_keywords');
    }
}
