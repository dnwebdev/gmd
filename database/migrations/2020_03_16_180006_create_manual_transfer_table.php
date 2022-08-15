<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManualTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_transfer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->index();
            $table->string('code_payment');
            $table->string('no_rekening');
            $table->string('name_rekening');
            $table->string('upload_document')->nullable();
            $table->string('status')->default('approved');
            $table->timestamps();

            $table->foreign('company_id')->references('id_company')->on('tbl_company')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manual_transfer');
    }
}
