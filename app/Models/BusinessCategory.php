<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessCategory extends Model
{
    protected $guarded = ['id'];
    protected $table = 'tbl_business_category';

    public function companies()
    {
        return $this->belongsToMany(Company::class,'business_category_company','business_category_id','company_id');
    }

    public function getBusinessCategoryNameIdAttribute($value)
    {
        if (empty($value)) {
            return $this->business_category_name;
        }

        return $value;
    }
}
