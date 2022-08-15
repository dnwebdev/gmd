<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class CreateUnitNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('unit_names')) {
            Schema::create('unit_names', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name_id');
                $table->string('name_en');
                $table->boolean('is_active')->default(false);
                $table->timestamps();
            });
        }

        Artisan::call('dump-autoload');
        Artisan::call('db:seed', ['--class' => 'UnitNameSeeder', '--force' => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unit_names');
    }
}
