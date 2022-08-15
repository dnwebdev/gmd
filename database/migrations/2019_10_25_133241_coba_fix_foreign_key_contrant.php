<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CobaFixForeignKeyContrant extends Migration
{
    private $_tables = [
        'tbl_guide_information',
        'tbl_payment_credit'
    ];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach ($this->_tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign(['invoice_no']);
            });

            DB::statement("ALTER TABLE `$table` CONVERT TO CHARACTER SET utf8mb4 COLLATE 'utf8mb4_unicode_ci';");

            Schema::table($table, function (Blueprint $table) {
                $table->foreign('invoice_no')
                    ->references('invoice_no')
                    ->on('tbl_order_header')
                    ->onDelete('cascade');;
            });
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_guide_information', function (Blueprint $table) {
            
        });
    }
}
