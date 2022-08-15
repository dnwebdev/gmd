<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class JobApplicant extends Model
{
    protected $guarded = [];
    public $incrementing = false;
    protected $table = 'job_applicants';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }

    public function job_vacancy()
    {
        return $this->belongsTo(GomodoCareer::class,'gomodo_career_id');
    }

}
