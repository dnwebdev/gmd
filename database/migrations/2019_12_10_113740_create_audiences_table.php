<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audiences', function (Blueprint $table) {
            $table->increments('id');
            $table->string('audience_name');
            $table->timestamps();
        });
        Schema::table('blogposts',function (Blueprint $table){
           $table->unsignedInteger('audience_id')->nullable();
           $table->foreign('audience_id')
               ->references('id')
               ->on('audiences')
               ->onDelete('cascade')
               ->onUpdate('cascade');
        });
        Schema::table('tag_blogs',function (Blueprint $table){
            $table->unsignedInteger('audience_id')->nullable();
            $table->foreign('audience_id')
                ->references('id')
                ->on('audiences')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('category_blogs',function (Blueprint $table){
            $table->unsignedInteger('audience_id')->nullable();
            $table->foreign('audience_id')
                ->references('id')
                ->on('audiences')
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
        Schema::table('category_blogs',function (Blueprint $table){
            $table->dropForeign(['audience_id']);
            $table->dropColumn(['audience_id']);
        });
        Schema::table('tag_blogs',function (Blueprint $table){
            $table->dropForeign(['audience_id']);
            $table->dropColumn(['audience_id']);
        });
        Schema::table('blogposts',function (Blueprint $table){
            $table->dropForeign(['audience_id']);
            $table->dropColumn(['audience_id']);
        });
        Schema::dropIfExists('audiences');
    }
}
