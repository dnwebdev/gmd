<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class ProductExport implements FromView, ShouldAutoSize
{

    use Exportable;

    private $_id = 'all';

    private $_tag_id = null;

    public function __construct($id = 'all', $tag_id = null)
    {
        $this->_id = $id;
        $this->_tag_id = $tag_id;
    }

    public function view(): View
    {
        $id = $this->_id;
        $tag_id = $this->_tag_id;

        $products = Product::with('company', 'city', 'city.state', 'city.state.country')
            ->when($tag_id, function ($query, $tag_id) {
                if ($tag_id != 'all') {
                    return $query->whereHas('tags', function ($query) use ($tag_id) {
                        return $query->where('tag_product_id', $tag_id);
                    });
                }
            })
            ->when($id, function ($query, $id) {
                if ($id != 'all') {
                    return $query->where('id_product', $id);
                }
            })
            ->whereHas('company', function ($query) {
                return $query->where('is_klhk', true);
            })
            ->withCount(['order_details as orders' => function ($query) {
                return $query->whereHas('invoice', function ($q) {
                        $q->whereIn('status', [0,1]);
                    })->select(DB::raw('IFNULL(SUM(adult), 0)'));
            }])
            ->has('city')
            ->get();

        return view('exports.product', compact('products'));
    }
}
