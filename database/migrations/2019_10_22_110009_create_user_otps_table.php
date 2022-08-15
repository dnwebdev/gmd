<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserOtpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try{
            DB::beginTransaction();
            Schema::create('user_otps', function (Blueprint $table) {
                $table->uuid('id');
                $table->primary(['id']);
                $table->unsignedInteger('user_id');
                $table->index(['user_id']);
                $table->string('otp');
                $table->string('shortcode');
                $table->timestamps();
                $table->foreign('user_id')
                    ->references('id_user_agen')
                    ->on('tbl_user_agent')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            });
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_otps');
    }
}
