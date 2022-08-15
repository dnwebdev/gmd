<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsurancePricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('insurance_pricings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('insurance_id')->index();
            $table->integer('day')->nullable();
            $table->double('price');
            $table->string('display_id');
            $table->string('display_en');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('insurance_id')
                ->references('id')
                ->on('insurances')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insurance_pricings');
    }
}
