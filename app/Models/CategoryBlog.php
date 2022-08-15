<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryBlog extends Model
{
    protected $table = 'category_blogs';
    protected $primaryKey = 'id';
    protected $guard = ['id'];

    protected $fillable = [
        'category_name_ind',
        'category_name_eng',
    ];

    public function blogPost()
    {
        return $this->hasMany('App\Models\BlogPost', 'category_blog_id', 'id');
    }

    public function post()
    {
        return $this->belongsTo('App\Models\BlogPost');
    }

    public function publishedPost()
    {
        return $this->hasMany(BlogPost::class,'category_blog_id')->publish();
    }

    public function audience()
    {
        return $this->belongsTo(Audience::class,'audience_id');
    }

    public function getCategoryNameAttribute()
    {
        if (app()->getLocale()=='id')
            return $this->category_name_ind;
        return $this->category_name_eng;
    }



}
