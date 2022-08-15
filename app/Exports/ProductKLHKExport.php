<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductKLHKExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function __construct()
    {
    }

    public function view(): View
    {
        $products = Product::with(['company', 'city', 'city.state', 'pricing', 'pricing.unit', 'first_schedule'])
            ->has('schedule')
            ->whereHas('company', function ($query) {
                return $query->where('is_klhk', 1);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('exports.klhk_product', compact('products'));
    }
}
