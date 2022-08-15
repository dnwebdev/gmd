<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerManualTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_manual_transfer', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('invoice_no',50)->collation(DB::select('SELECT COLLATION_NAME FROM information_schema.columns WHERE TABLE_NAME = \'tbl_order_header\' AND COLUMN_NAME = \'invoice_no\'')[0]->COLLATION_NAME);
            $table->index(['invoice_no']);
            $table->string('bank_name');
            $table->string('no_rekening');
            $table->string('upload_document')->nullable();
            $table->string('note_customer')->nullable();
            $table->string('status')->default('need_confirmed');
            $table->timestamps();
            $table->foreign('invoice_no')
                ->references('invoice_no')
                ->on('tbl_order_header')
                ->onUpdate('cascade')
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
        Schema::dropIfExists('customer_manual_transfer');
    }
}
