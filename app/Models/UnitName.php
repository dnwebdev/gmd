<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitName extends Model
{
    protected $fillable = ['name_id', 'name_en', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getNameAttribute()
    {
        return $this->{'name_'.app()->getLocale()};
    }
}
