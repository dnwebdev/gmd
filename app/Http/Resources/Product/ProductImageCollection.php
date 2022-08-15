<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductImageCollection extends ResourceCollection
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
            'data' => $this->collection->map(function ($t) {
                return [
                    'id' => $t->id_image,
                    'link' => asset('uploads/products/thumbnail/'.$t->url),
                ];
            })
        ];
    }
}
