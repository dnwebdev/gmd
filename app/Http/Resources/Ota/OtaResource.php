<?php

namespace App\Http\Resources\Ota;

use Illuminate\Http\Resources\Json\Resource;

class OtaResource extends Resource
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
            'id' => $this->id,
            'name' => $this->ota_name,
            'slug' => $this->ota_slug,
            'image' => asset($this->ota_icon),
            'status' => $this->ota_status == 1,
            'original_markup' => (float) $this->ota_original_markup,
            'gomodo_markup' => (float) $this->ota_gomodo_markup,
            'updated_at' => $this->updated_at->toDateTimeString(),
            'created_at' => $this->created_at->toDateTimeString()
        ];
    }
}
