<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Region\City\CityResource;
use Illuminate\Http\Resources\Json\Resource;

class ProductResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id_product,
            'product_type' => ProductTypeResource::make($this->product_type),
            'url' => 'http://' . $this->company->domain_memoria . '/product/detail/' . $this->unique_code,
            'product_image' => asset($this->main_image == 'img2.jpg' ? 'img/no-product-image.png' : 'uploads/products/thumbnail/' . $this->main_image),
            'images' => ProductImageCollection::make($this->image),
            'product_name' => $this->product_name,
            'sku' => $this->unique_code,
            'short_description' => $this->brief_description,
            'description' => $this->long_description,
            'city' => CityResource::make($this->city),
            'tags' => $this->tags->take(2)->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' =>  app()->getLocale() =='id'?$tag->name_ind:$tag->name,
                ];
            }),
            'location' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude
            ],
            'discount' => [
                'discount_name' => $this->discount_name,
                'discount_type' => $this->discount_amount_type == '0' ? 'Fixed' : 'Percentage',
                'discount_amount' => $this->discount_amount
            ],
            'duration' => [
                'value' => $this->duration,
                'type' => $this->duration_type == '0' ? trans('customer.invoice.hour') : __('customer.detail.day')
            ],
            'viewed' => $this->viewed,
            'itinerary' => $this->itineraryCollection,
            'price' => ProductPriceCollection::make($this->pricing()->orderBy('price_from', 'asc')->get()),
            'schedule' => $this->schedule()
                ->orderBy('id_schedule', 'desc')
                ->first(),
            'additionals'=>$this->customSchema
        ];
    }
}
