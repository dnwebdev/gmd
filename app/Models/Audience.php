<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audience extends Model
{
    protected $guarded = [];

    public function scopeAudienceType($query,$type)
    {
        return $query->where('audience_name',$type);
    }
}
