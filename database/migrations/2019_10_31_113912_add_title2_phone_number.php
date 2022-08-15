<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTitle2PhoneNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ads', function (Blueprint $table) {
            $table->string('title2')->after('title')->nullable();
            $table->string('phone_number', 20)->after('payment_method_id')->nullable();
            $table->text('language')->nullable()->after('payment_method_id')->comment('Dalam bentuk json');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_ads', function (Blueprint $table) {
            $table->dropColumn('title2');
            $table->dropColumn('phone_number');
            $table->dropColumn('language');
        });
    }
}
