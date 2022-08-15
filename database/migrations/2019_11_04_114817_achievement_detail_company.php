<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AchievementDetailCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('achievement_detail_company', function (Blueprint $table) {
            $table->unsignedInteger('achievement_id');
            $table->integer('company_id')->index();
            $table->boolean('achievement_status')->default(0);
        });
        DB::statement('ALTER TABLE `achievement_detail_company` CHANGE `company_id` `company_id` INT(8) NOT NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('achievement_detail_company');
    }
}
