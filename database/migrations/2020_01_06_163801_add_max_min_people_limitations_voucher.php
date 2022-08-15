<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMaxMinPeopleLimitationsVoucher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_voucher', function (Blueprint $table) {
            $table->integer('min_people')
                ->default(1)
                ->comment('Min people');
            $table->integer('max_people')
                ->nullable()
                ->comment('Max people');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_voucher', function (Blueprint $table) {
            $table->dropColumn(['min_people', 'max_people']);
        });
    }
}
