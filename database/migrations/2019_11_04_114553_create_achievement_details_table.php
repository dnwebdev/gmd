<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAchievementDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('achievement_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('achievement_id');
            $table->string('slug');
            $table->string('title_en');
            $table->string('title_id');
            $table->timestamps();
            $table->foreign('achievement_id')
                ->references('id')
                ->on('achievements')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('achievement_details');
    }
}
