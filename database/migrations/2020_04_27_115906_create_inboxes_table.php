<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInboxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inboxes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('menu_id')->index()->nullable();
            $table->uuid('contact_id')->index();
            $table->ipAddress('ip')->nullable();
            $table->longText('message')->nullable();
            $table->boolean('has_reply')->default(false);
            $table->timestamps();
            $table->foreign('menu_id')->references('id')->on('menu_bots')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('contact_id')->references('id')->on('woowa_contacts')
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
        Schema::dropIfExists('inboxes');
    }
}
