<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GomodoCareer extends Model
{
    protected $guarded = [];

    public function applicants()
    {
        return $this->hasMany(JobApplicant::class,'gomodo_career_id');
    }
}
