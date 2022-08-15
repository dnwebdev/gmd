<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class AchievementDetail extends Model
{
    protected $guarded = [];

    public function achievement()
    {
        return $this->belongsTo(Achievement::class,'achievement_id');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class,'achievement_detail_company','achievement_id',
                'company_id')->withPivot('achievement_status');

    }
}
