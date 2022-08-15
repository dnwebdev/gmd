<?php

use Illuminate\Database\Seeder;
use App\Models\UnitName;
use App\Models\ProductPrice;

class DisplayNameToUnitPricingMigration extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Lookup terlebih dahulu untuk menghindari N+1
        $unit_names = UnitName::all();
        $unit_id = $unit_names->mapWithKeys(function ($value) {
            return [$value->name_id => $value->id];
        })->toArray();

        $unit_en = $unit_names->mapWithKeys(function ($value) {
            return [$value->name_en => $value->id];
        })->toArray();

        $units = array_merge($unit_id, $unit_en);

        // Update Product pricing, set unit name idnya
        $prices = ProductPrice::whereNull('unit_name_id')->get();
        foreach ($prices as $price) {
            $price->update([
                'unit_name_id' => $units[$price->display_name] ?? null
            ]);
        }
    }
}
