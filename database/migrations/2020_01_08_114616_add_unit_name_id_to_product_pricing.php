<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class AddUnitNameIdToProductPricing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_product_pricing', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_product_pricing', 'unit_name_id')) {
                $table->unsignedInteger('unit_name_id')
                    ->after('display_name')
                    ->nullable();

                $table->foreign('unit_name_id')
                    ->references('id')
                    ->on('unit_names')
                    ->onDelete('set null')
                    ->onUpdate('cascade');
            }
        });

        Artisan::call('dump-autoload');
        Artisan::call('db:seed', ['--class' => 'DisplayNameToUnitPricingMigration', '--force' => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_pricing', function (Blueprint $table) {
            $table->dropForeign(['unit_name_id']);
            $table->dropColumn('unit_name_id');
        });
    }
}
