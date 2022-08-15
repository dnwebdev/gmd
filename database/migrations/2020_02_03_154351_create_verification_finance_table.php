<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class CreateVerificationFinanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verification_finance', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('finance_id');
            $table->string('ktp')->nullable();
            $table->string('npwp')->nullable();
            $table->string('siup')->nullable();
            $table->string('founding_deed')->nullable();
            $table->string('change_certificate')->nullable();
            $table->string('sk_menteri')->nullable();
            $table->string('company_signatures')->nullable();
            $table->string('report_statement')->nullable();
            $table->string('document_bank')->nullable();
            $table->string('ktp_couples')->nullable();
            $table->string('family_card')->nullable();
            $table->timestamps();

            $table->foreign('finance_id')->references('id')->on('finance')->onUpdate('cascade')->onDelete('cascade');

            Artisan::call('dump-autoload');
            Artisan::call('db:seed', ['--class' => 'FinanceSeeder', '--force' => true]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verification_finance');
    }
}
