<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoogleReviewWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_review_widgets', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->integer('company_id')->index();
            $table->text('widget_script')->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE `google_review_widgets` CHANGE `company_id` `company_id` INT(8) NOT NULL;');
        Schema::table('google_review_widgets', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id_company')
                ->on('tbl_company')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('tbl_company', function (Blueprint $table) {
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('google_review_widgets');
        Schema::table('tbl_company', function (Blueprint $table) {
            $table->dropColumn('lat');
            $table->dropColumn('long');
        });
    }
}
