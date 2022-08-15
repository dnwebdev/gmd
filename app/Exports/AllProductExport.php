<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AllProductExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function __construct()
    {
    }

    public function view(): View
    {
        $products = Product::with(['company', 'city', 'city.state', 'pricing', 'pricing.unit', 'first_schedule'])
            ->has('schedule')
            ->has('company')
            ->orderBy('created_at', 'desc')
            ;

        return view('exports.allproduct', compact('products'));
    }
}
