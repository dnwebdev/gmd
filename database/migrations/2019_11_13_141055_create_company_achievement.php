<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyAchievement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_achievement', function (Blueprint $table) {
            $table->unsignedInteger('achievement_id');
            $table->integer('company_id')->index();
            $table->boolean('company_achievement_status')->default(0);
            $table->boolean('claimed')->default(0);
        });

        DB::statement('ALTER TABLE `company_achievement` CHANGE `company_id` `company_id` INT(8) NOT NULL;');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_achievement');
    }
}
