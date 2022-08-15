<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferalCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_company', function (Blueprint $table) {
            $table->string('referral_code')->nullable();
            $table->unsignedInteger('parent_id')->nullable();
        });
        DB::statement('ALTER TABLE `tbl_company` CHANGE `parent_id` `parent_id` INT(8)');
        foreach (\App\Models\Company::all() as $c):
            $random = "MGM" . generateRandomString(9);
            while (\App\Models\Company::where('referral_code', $random)->first()) {
                $random = "MGM" . generateRandomString(9);
            }
            $c->update(['referral_code'=>$random]);
        endforeach;

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_company', function (Blueprint $table) {
            $table->dropColumn(['referral_code', 'parent_id']);
        });
    }
}
