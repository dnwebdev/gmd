<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function myProduct()
    {
        return apiResponse(
            200,
            'OK',
            new ProductCollection(Product::where('id_company', 52)
                ->paginate(12))
        );
    }

    public function listProduct(Request $request)
    {
        $company = auth('api')->user()->company;
        $products = Product::where('id_company', $company->id_company)->where('publish',
            1)->availableQuota();
        if ($request->has('keyword') && $request->get('keyword') !== null && $request->get('keyword') !== '') {
            $products = $products->where('tbl_product.product_name', 'like',
                '%' . trim($request->get('keyword')) . '%');
        }
        if ($request->has('city') && $request->get('city') !== null && $request->get('city') !== '') {
            $products = $products->where('id_city', $request->get('city'));
        }
        if ($request->has('sort') && $request->get('sort') !== null && $request->get('sort') !== '') {
            switch ($request->get('sort')) {
                case 'name_asc':
                    $products->orderBy('tbl_product.product_name', 'ASC');
                    break;
                case 'name_desc':
                    $products->orderBy('tbl_product.product_name', 'DESC');
                    break;
                case 'newest':
                    $products->orderBy('tbl_product.created_at', 'DESC');
                    break;
                case 'oldest':
                    $products->orderBy('tbl_product.created_at', 'ASC');
                    break;
                case 'cheapest':
                    $products->selectRaw("tbl_product.*, MIN(tbl_product_pricing.price) as min_price")->join('tbl_product_pricing',
                        'tbl_product_pricing.id_product', '=', 'tbl_product.id_product')
                        ->groupBy('tbl_product_pricing.id_product')->orderBy('min_price', 'ASC');
                    break;
                case 'most_expensive':
                    $products->selectRaw("tbl_product.*, MIN(tbl_product_pricing.price) as min_price")->join('tbl_product_pricing',
                        'tbl_product_pricing.id_product', '=', 'tbl_product.id_product')
                        ->groupBy('tbl_product_pricing.id_product')->orderBy('min_price', 'DESC');
                    break;
            }

        }
        $products = $products->paginate(12);
        return apiResponse(200,
            'OK', new ProductCollection($products));
    }

    public function detailProduct($id_product, Request $request)
    {
        $product = Product::where(['unique_code' => $id_product])->first();
        if (!$product) {
            return apiResponse(404,'Not Found');
        }
        $company = $product->company;
        $product->increment('viewed');
        return  apiResponse(200,'OK',new ProductResource($product));
    }
}
