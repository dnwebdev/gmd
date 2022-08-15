<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;

class TagBlog extends Model
{
    protected $table = 'tag_blogs';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'tag_name_ind',
        'tag_name_eng',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }

    public function postTag()
    {
        return $this->belongsToMany('App\Models\TagBlog','join_tag_blogs', 'blogpost_id','tag_blog_id');
    }
    public function audience()
    {
        return $this->belongsTo(Audience::class,'audience_id');
    }
}
