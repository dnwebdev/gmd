<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsuranceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insurance_forms', function (Blueprint $table) {
            if (!Schema::hasColumn('insurance_forms','deleted_at')) {
                $table->softDeletes();
            }
        });
        if (!Schema::hasTable('insurance_details')) {
            Schema::create('insurance_details', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('additional_order_id');
                $table->index(['additional_order_id']);
                $table->integer('purpose_order')->default(1);
                $table->unsignedInteger('insurance_form_id');
                $table->string('label_id');
                $table->string('label_en');
                $table->text('value');
                $table->string('purpose')->default('participants');
                $table->string('type')->default('text');
                $table->timestamps();
            });
        }
         Schema::table('insurance_details', function (Blueprint $table){
            $table->foreign('additional_order_id')
                ->references('id')
                ->on('additional_orders')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('insurance_form_id')
                ->references('id')
                ->on('insurance_forms')
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

        Schema::dropIfExists('insurance_details');
        Schema::table('insurance_forms', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
