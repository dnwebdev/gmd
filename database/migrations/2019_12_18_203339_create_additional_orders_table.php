<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdditionalOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_orders', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('invoice_no',50)->collation(DB::select('SELECT COLLATION_NAME FROM information_schema.columns WHERE TABLE_NAME = \'tbl_order_header\' AND COLUMN_NAME = \'invoice_no\'')[0]->COLLATION_NAME);
            $table->index(['invoice_no']);
            $table->integer('quantity')->default(1);
            $table->double('price')->default(0);
            $table->double('total')->default(0);
            $table->text('description_id')->nullable();
            $table->text('description_en')->nullable();
            $table->string('type')->default('insurance');

            $table->foreign('invoice_no')
                ->references('invoice_no')
                ->on('tbl_order_header')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('additional_orders');
    }
}
