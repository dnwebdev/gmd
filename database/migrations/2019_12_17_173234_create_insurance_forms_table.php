<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsuranceFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('insurance_id');
            $table->string('name');
            $table->string('label_id');
            $table->string('label_en');
            $table->string('type');
            $table->string('class')->nullable();
            $table->text('options')->nullable();
            $table->boolean('required')->default(1);
            $table->string('purpose')->default('participants');
            $table->timestamps();

            $table->foreign('insurance_id')
                ->references('id')
                ->on('insurances')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Artisan::call('db:seed',['--class'=>'InsuranceSeeder','--force'=>true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insurance_forms');
    }
}
