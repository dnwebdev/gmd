<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsuranceForm extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    protected $casts = [
        'options' => 'array'
    ];

    public function getLabelAttribute()
    {
        if (app()->getLocale() == 'id')
            return $this->label_id;

        return $this->label_en;
    }

}
