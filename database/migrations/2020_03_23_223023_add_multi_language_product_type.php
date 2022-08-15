<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\ProductType;

class AddMultiLanguageProductType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_product_type', function (Blueprint $table) {
            $table->string('product_type_name_id')
                ->length(100)
                ->after('product_type_name')
                ->nullable();
        });

        $indonesia = collect([
            'Other Activities' => 'Aktivitas Lainnya',
            'Day Tour' => 'Tur Harian',
            'Cash Voucher' => 'Voucer Uang',
            'Custom Trip' => 'Trip Kustom',
            'Package Trip' => 'Paket Trip',
            'Open Trip' => 'Open Trip'
        ]);

        // Translate ke indo
        $indonesia->each(function ($id, $en) {
            ProductType::where('product_type_name', $en)
                ->update([
                    'product_type_name_id'  => $id
                ]);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_product_type', function (Blueprint $table) {
            $table->dropColumn('product_type_name_id');
        });
    }
}
