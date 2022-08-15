<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScheduleIdToOrderDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_order_detail', function (Blueprint $table) {
            $table->integer('id_schedule')
                ->length(11)
                ->after('invoice_no')
                ->nullable();

            $table->foreign('id_schedule')
                ->references('id_schedule')
                ->on('tbl_product_schedule')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_order_detail', function (Blueprint $table) {
            $table->dropForeign(['id_schedule']);
            $table->dropColumn('id_schedule');
        });
    }
}
