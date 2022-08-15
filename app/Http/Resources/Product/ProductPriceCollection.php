<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductPriceCollection extends ResourceCollection
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
                $display = optional($t->unit)->name;
                return [
                    'id' => $t->id_price,
                    'currency' => $t->currency,
                    'display' => $display,
                    'price' => $t->price,
                    'price_text' => $t->currency . ' ' . number_format($t->price, 0),
                    'quantity_from' => $t->price_from,
                    'quantity_until' => $t->price_until,
                ];
            })
        ];
    }
}
