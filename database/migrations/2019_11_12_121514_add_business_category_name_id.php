<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBusinessCategoryNameId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_business_category', function (Blueprint $table) {
            $table->string('business_category_name_id')->after('business_category_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_business_category', function (Blueprint $table) {
            $table->dropColumn(['business_category_name_id']);
        });
    }
}
