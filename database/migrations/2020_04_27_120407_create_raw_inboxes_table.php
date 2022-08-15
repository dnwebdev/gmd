<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRawInboxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_inboxes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->longText('raw');
            $table->timestamps();
            
        });
        \Artisan::call('db:seed', ['--class' => 'MenuBotTableSeeder', '--force' => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raw_inboxes');
    }
}
