<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsuranceRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('company_id')->index();
            $table->string('phone');
            $table->string('email');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE `insurance_requests` CHANGE `company_id` `company_id` INT(8);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insurance_requests');
    }
}
