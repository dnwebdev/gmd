<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CobaFixTableKorup extends Migration
{
    protected $table = 'tbl_guide_information';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("REPAIR TABLE `$this->table`");
        DB::statement("OPTIMIZE TABLE `$this->table`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
