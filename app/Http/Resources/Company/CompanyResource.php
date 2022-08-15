<?php

namespace App\Http\Resources\Company;

use Illuminate\Http\Resources\Json\Resource;

class CompanyResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        $array = [
            'id' => $this->id_company,
            'company_name'=>$this->company_name,
            'status'=>$this->status =='1'?'active':'Non Active',
            'gomodo_domain'=>$this->domain_memoria,
            'company_email'=>$this->email_company,
            'company_phone'=>$this->phone_company,
            'title'=>$this->title,
            'description'=>$this->description,
            'keywords'=>$this->keywords,
            'ga_code'=>$this->ga_code,
            'banner'=>$this->banner_url,
            'logo'=>$this->logo_url
        ];

        return  $array;
    }
}
