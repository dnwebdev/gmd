<?php

namespace App\Http\Resources\Region\City;

use App\Http\Resources\Region\State\StateResource;
use Illuminate\Http\Resources\Json\Resource;

class CityResource extends Resource
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
            'id' => $this->id_city,
            'city_name' =>  app()->getLocale() =='id'?$this->city_name:$this->city_name_en,
            'state'=>StateResource::make($this->state)
        ];
    }
}
