<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    protected $guarded = [];

    public function products()
    {
        return $this->belongsToMany(Product::class,'product_insurance','insurance_id','product_id');
    }

    public function pricings()
    {
        return $this->hasMany(InsurancePricing::class,'insurance_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

    public function active_pricings()
    {
        return $this->hasMany(InsurancePricing::class,'insurance_id')->where('status',1);
    }

    public function getInsuranceNameAttribute()
    {
        if (app()->getLocale() =='id'){
            return $this->insurance_name_id;
        }
        return $this->insurance_name_en;
    }

    public function getInsuranceDescriptionAttribute()
    {
        if (app()->getLocale() =='id'){
            return $this->insurance_description_id;
        }
        return $this->insurance_description_en;
    }

    public function getInsuranceLogoUrlAttribute()
    {
        return asset($this->insurance_logo);
    }

    public function forms()
    {
        return $this->hasMany(InsuranceForm::class,'insurance_id');
    }

    public function customer_forms()
    {
        return $this->hasMany(InsuranceForm::class,'insurance_id')->where('purpose','customer');
    }

    public function participant_forms()
    {
        return $this->hasMany(InsuranceForm::class,'insurance_id')->where('purpose','participants');
    }

}


