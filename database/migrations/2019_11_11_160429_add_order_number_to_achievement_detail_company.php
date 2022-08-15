<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderNumberToAchievementDetailCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('achievement_details', function (Blueprint $table) {
            $table->integer('order_number')->nullable()->after('title_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('achievement_details', function (Blueprint $table) {
            $table->dropColumn(['order_number']);
        });
    }
}
