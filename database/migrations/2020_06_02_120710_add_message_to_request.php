<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMessageToRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('distribution_requests', function (Blueprint $table) {
            $table->text('message');
        });
        Schema::table('insurance_requests', function (Blueprint $table) {
            $table->text('message');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('distribution_requests', function (Blueprint $table) {
            $table->dropColumn(['message']);
        });
        Schema::table('insurance_requests', function (Blueprint $table) {
            $table->dropColumn(['message']);
        });
    }
}
