<?php

namespace App\Http\Controllers\Api\Ota;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Ota\ProductListRequest;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(ProductListRequest $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 12);
        $ota = $request->query('ota');
        $provider = $request->query('provider');
        $status = $request->query('status');

        $product = Product::has('ota')
            ->when($search, function ($query, $search) {
                return $query->where('product_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('unique_code', 'LIKE', '%'.$search.'%');
            })
            ->when($ota, function ($query, $ota) {
                return $query->whereHas('ota', function ($query) use ($ota) {
                    if (is_array($ota))
                        return $query->whereIn('ota_slug', $ota);
                    
                    return $query->where('ota_slug', $ota);
                });
            })
            ->when($provider, function ($query, $provider) {
                return $query->whereHas('company', function ($query) use ($provider) {
                    return $query->where('company_name', 'LIKE', '%'.$provider.'%')
                        ->orWhere('domain_memoria', 'LIKE', '%'.$provider.'%');
                });
            })
            ->when($status, function ($query, $status) {
                return $query->whereHas('ota', function ($query) use ($status) {
                    return $query->where('status', $status);
                });
            })
            ->paginate($limit);

            return $product;
    }
}