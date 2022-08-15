<?php

namespace App\Exports\Custom;

use App\Models\Product;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProviderGmv implements FromView, ShouldAutoSize
{
    use Exportable;

    public function __construct()
    {
    }

    public function view(): View
    {
        $products = Product::with(['company', 'order_detail'])->where('booking_type', 'online')
            ->has('order_detail')
            ->whereHas('company', function ($query) {
                return $query->where('created_at', '>', Carbon::parse('2019-02-01 00:00:00'))->where('updated_at', '>', Carbon::parse('2019-02-01 00:00:00'));
            })
            ->orderBy('id_company', 'desc')
            ->get();

        return view('exports.gmv_provider', compact('products'));
    }
}
