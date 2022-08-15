<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInsuranceIdOnOrderDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_order_detail', function (Blueprint $table) {
            $table->unsignedInteger('insurance_pricing_id')->nullable();
            $table->foreign('insurance_pricing_id')
                ->references('id')
                ->on('insurance_pricings')
                ->onUpdate('cascade')
                ->onDelete('set null');
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
            $table->dropForeign(['insurance_pricing_id']);
            $table->dropColumn(['insurance_pricing_id']);
        });
    }
}
