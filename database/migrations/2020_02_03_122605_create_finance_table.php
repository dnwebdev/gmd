<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('type_finance_id')->unsigned();
            $table->integer('company_id')->index();
            $table->integer('time_finance_id')->unsigned();
            $table->double('min_suku_bunga')->nullable()->default(0);
            $table->double('max_suku_bunga')->nullable()->default(0);
            $table->double('fee_provisi')->nullable()->default(0);
            $table->double('fee_penalty_delay')->nullable()->default(0);
            $table->double('fee_settled')->nullable()->default(0);
            $table->double('fee_admin')->nullable()->default(0);
            $table->double('fee_insurance')->nullable()->default(0);
            $table->double('amount')->default(0);
            $table->string('status')->default(0);
            $table->timestamps();

            $table->foreign('company_id')->references('id_company')->on('tbl_company')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('type_finance_id')->references('id')->on('type_finance')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('time_finance_id')->references('id')->on('time_finance')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('finance');
    }
}
