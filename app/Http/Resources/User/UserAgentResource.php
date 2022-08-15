<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Company\CompanyResource;
use Illuminate\Http\Resources\Json\Resource;

class UserAgentResource extends Resource
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
            'id' => $this->id_user_agen,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone'=>$this->phone,
            'date_register'=>$this->created_at->format('d M Y'),
            'status'=>$this->status =='1'?'Active':'Non Active',
            'preferred_language'=>$this->language,
            'company'=>CompanyResource::make($this->whenLoaded('company'))
        ];
    }
}
