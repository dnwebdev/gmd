
<?php

use Illuminate\Database\Seeder;
use App\Models\UnitName;
use App\Models\OrderDetail;

class DisplayNameToUnitOrderDetailMigration extends Seeder
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
        $orders = OrderDetail::whereNull('unit_name_id')->get();
        foreach ($orders as $order) {
            $order->update([
                'unit_name_id' => $units[$order->display_name] ?? null
            ]);
        }
    }
}
