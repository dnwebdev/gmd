<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeCollationCharsetToUtf8 extends Migration
{

    private $_tables = [
        'tbl_product',
        'tbl_order_extra',
        'tbl_order_header',
        'tbl_company',
        'tbl_order_customer',
        'tbl_order_detail',
        'tbl_payment',
        'tbl_product_category',
        'tbl_customer',
        'tbl_product_pricing',
        //'tbl_guide_information',
        //'tbl_payment_credit'
    ];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*DB::statement('SET FOREIGN_KEY_CHECKS=0');
        // Ganti charset ke default charset laravel daripada latin
        foreach ($this->_tables as $table) {
            DB::statement("ALTER TABLE `${table}` CONVERT TO CHARACTER SET utf8mb4 COLLATE 'utf8mb4_unicode_ci';");
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
        /*DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach ($this->_tables as $table) {
            DB::statement("ALTER TABLE `${table}` CONVERT TO CHARACTER SET latin1 COLLATE 'latin1_swedish_ci';");
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');*/
    }
}
