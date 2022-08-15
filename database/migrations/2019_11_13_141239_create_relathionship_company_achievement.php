<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelathionshipCompanyAchievement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_achievement', function (Blueprint $table) {
            $table->foreign('achievement_id')
                ->references('id')
                ->on('achievements')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('company_id')
                ->references('id_company')
                ->on('tbl_company')
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
        Schema::table('company_achievement', function (Blueprint $table) {
            $table->dropForeign(['achievement_id']);
            $table->dropForeign(['company_id']);
        });
    }
}
