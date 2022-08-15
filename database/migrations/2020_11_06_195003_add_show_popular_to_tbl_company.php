<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShowPopularToTblCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_company', function (Blueprint $table) {
            $table->boolean('show_popular')->default(true);
        });
        DB::table('tbl_company')->where('domain_memoria', 'turbro.'.env('APP_URL'))
            ->update(['show_popular' => false]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_company', function (Blueprint $table) {
            $table->dropColumn('show_popular');
        });
    }
}
