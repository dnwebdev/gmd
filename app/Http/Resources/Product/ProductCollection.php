<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Region\City\CityResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'data' => $this->collection->map(function ($product) {
                return [
                    'id' => $product->id_product,
                    'url'=>'http://'.$product->company->domain_memoria.'/product/detail/'.$product->unique_code,
                    'product_image'=>asset($product->main_image=='img2.jpg'?'img/no-product-image.png':'uploads/products/thumbnail/'.$product->main_image),
                    'product_name' => $product->product_name,
                    'sku' => $product->unique_code,
                    'short_description' => $product->brief_description,
                    'city' => CityResource::make($product->city),
                    'tags' => $product->tags->take(2)->map(function ($tag) {
                        return [
                            'id' => $tag->id,
                            'name' =>  app()->getLocale() =='id'?$tag->name_ind:$tag->name,
                        ];
                    }),
                    'discount' => [
                        'discount_name' => $product->discount_name,
                        'discount_type' => $product->discount_amount_type == '0' ? 'Fixed' : 'Percentage',
                        'discount_amount' => $product->discount_amount
                    ],
                    'duration' => [
                        'value' => $product->duration,
                        'type' => $product->duration_type == '0' ? 'Hour' : 'Day'
                    ],
                    'viewed' => $product->viewed,
                    'price_start'=>[
                        'original'=>$product->pricing()->orderBy('price','asc')->first()->price,
                        'text'=>$product->currency.' '.number_format($product->pricing()->orderBy('price','asc')->first()->price,0)
                    ],
                    'schedule'=>$product->schedule()
                        ->orderBy('id_schedule','desc')
                        ->first()
                        ->only('id_schedule','start_date','end_date')
                ];
            }),
            'pagination' => [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage(),
                'previous_page' => $this->previousPageUrl(),
                'next_page' => $this->nextPageUrl()
            ],
        ];

    }

}
