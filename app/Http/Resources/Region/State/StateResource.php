<?php

namespace App\Http\Resources\Region\State;

use App\Http\Resources\Region\Country\CountryResource;
use Illuminate\Http\Resources\Json\Resource;

class StateResource extends Resource
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
            'id' => $this->id_state,
            'state_name' => app()->getLocale() =='id'?$this->state_name:$this->state_name_en,
            'country'=>CountryResource::make($this->country)
        ];
    }
}
