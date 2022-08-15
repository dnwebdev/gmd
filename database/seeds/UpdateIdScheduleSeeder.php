<?php

use Illuminate\Database\Seeder;
use App\Models\OrderDetail;

class UpdateIdScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->getOutput()->progressStart(OrderDetail::whereNull('id_schedule')->count());
        OrderDetail::whereNull('id_schedule')
            ->with('product:id_product,product_name')
            ->chunk(100, function ($orders) {
                foreach ($orders as $order) {
                    // Ini loh tanggal jadiannya
                    $date = $order->schedule_date;

                    // Status ketersediannya apa dulu
                    $availability = $order->availability;

                    // Gunakan lazy eager loading, untuk performa
                    $order->load(['product.first_schedule' => function ($query) use ($date, $availability) {
                        return $query->where('start_date', '<=', $date)
                            ->when($availability, function ($q, $availability) use ($date) {
                                if ($availability == 1) {
                                    return $q->where('end_date', '>=', $date);
                                }
                            });
                    }]);

                    // cek apakah punya jadwal
                    if (empty($order->product->first_schedule)) {
                        // coba sekali lagi dengan waktu tanpa filter
                        $order->load('product.first_schedule');

                        // Cek lagi jadwalnya
                        if (empty($order->product->first_schedule)) {
                            echo PHP_EOL;
                            $this->command->error('Order dengan invoice '.$order->invoice_no.', produk '.$order->product->product_name.' ('.$order->id_product.') tidak berhasil karena tidak ada jadwal yang sesuai');

                            // Jika tetap tidak ditemukan maka ya sudahlah, menyerah saja masih ada yang lain
                            continue;
                        }
                    }

                    $order->update([
                        'id_schedule'   => $order->product->first_schedule->id_schedule
                    ]);

                    $this->command->getOutput()->progressAdvance();

                }
            });
        $this->command->getOutput()->progressFinish();
    }
}
