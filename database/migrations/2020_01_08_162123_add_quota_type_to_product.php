<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQuotaTypeToProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_product', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_product', 'quota_type')) {
                $table->enum('quota_type', ['periode', 'day'])
                    ->default('periode');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_product', function (Blueprint $table) {
            $table->dropColumn('quota_type');
        });
    }
}
