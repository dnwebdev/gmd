<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsBusinessCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_business_categories', function (Blueprint $table) {
            $table->charset = 'latin1';
            $table->collation = 'latin1_swedish_ci';
            $table->integer('ads_id', false, false)->length(11);
            $table->integer('business_category_id', false, false)->length(10);
            
            $table->foreign('ads_id')
                ->references('id')
                ->on('tbl_ads')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('business_category_id')
                ->references('id')
                ->on('tbl_business_category')
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
        Schema::dropIfExists('ads_business_categories');
    }
}
