<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblOrderHeaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('tbl_order_header', 'payment_list')) {
            Schema::table('tbl_order_header', function (Blueprint $table) {
                $table->string('payment_list')->nullable()->default('Xendit Payment')->after('payment_type');
            });
        }

        Schema::table('tbl_payment', function (Blueprint $table){
            if (!Schema::hasColumn('tbl_payment', 'token_midtrans')) {
                $table->string('token_midtrans')->nullable()->after('reference_number');
            }
            if (!Schema::hasColumn('tbl_payment', 'pay_at')) {
                $table->string('pay_at')->nullable()->after('updated_at');
            }
            if (!Schema::hasColumn('tbl_payment', 'response_midtrans')) {
                $table->longText('response_midtrans')->nullable()->after('response');
            }
            if (!Schema::hasColumn('tbl_payment', 'settlement_on')) {
                $table->timestamp('settlement_on')->nullable()->after('pay_at');
            }
        });
        $order = \App\Models\Order::query();
        $order->update([
            'is_read'=>1,
            'updated_at'=>DB::raw('updated_at')
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_order_header', function (Blueprint $table) {
            $table->dropColumn(['payment_list']);
        });
        Schema::table('tbl_payment', function (Blueprint $table){
            $table->dropColumn(['token_midtrans','pay_at','response_midtrans','settlement_on']);
        });
    }
}
