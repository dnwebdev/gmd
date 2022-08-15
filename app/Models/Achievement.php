<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(AchievementDetail::class,'achievement_id');
    }
}
