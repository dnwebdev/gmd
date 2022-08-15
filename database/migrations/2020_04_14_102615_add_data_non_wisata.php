<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataNonWisata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $newData = [
            'product_type_name' => 'Services (Non-Travel)',
            'product_type_name_id' => 'Jasa (Non-Wisata)',
            'product_type_description'=>'Jasa non wisata'
        ];
        $productType = \App\Models\ProductType::firstOrCreate($newData);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
