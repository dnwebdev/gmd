<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Nicolaslopezj\Searchable\SearchableTrait;

class BlogPost extends Model
{
    use SearchableTrait;
    protected $table = 'blogposts';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $guarded = ['viewed', 'page'];

    protected $searchable = [
        'columns' => [
            'blogposts.title_ind' => 5,
            'blogposts.title_eng' => 5,
            'category_blogs.category_name_ind' => 5,
            'category_blogs.category_name_eng' => 5,
        ],
        'joins' => [
            'category_blogs' => ['blogposts.category_blog_id', 'category_blogs.id']
        ]
    ];


    protected $fillable = [
        'title_ind', 'title_eng', 'description_ind', 'description_eng', 'slug', 'image_blog', 'category_blog_id',
        'is_published', 'admin_id'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }
    public function audience()
    {
        return $this->belongsTo(Audience::class,'audience_id');
    }

    public function categoryBlog()
    {
//        return $this->belongsTo('App\Models\CategoryBlog', 'category_blog_id', 'id');
        return $this->belongsTo(CategoryBlog::Class, 'category_blog_id');
    }

    public function cateBlog()
    {
        return $this->hasMany('App\Models\CategoryBlog', 'id', 'category_blog_id');
    }

    public function tagPost()
    {
        return $this->belongsToMany('App\Models\TagBlog', 'join_tag_blogs', 'blogpost_id', 'tag_blog_id');
    }

    public function tagValue()
    {
        return $this->hasMany(TagBlogPost::class, 'blogpost_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function getImageUrlAttribute()
    {
        return asset($this->image_blog);
    }

    public function scopeBlog($query)
    {
        return $query->where('type_post','blog');
    }

    public function scopePressRelease($query)
    {
        return $query->where('type_post','press-releases');
    }

    public function scopePublish($query)
    {
        return $query->where('is_published','1');
    }

    public function getTitleAttribute()
    {
        if (app()->getLocale()=='id')
            return $this->title_ind;
        return $this->title_eng;
    }

    public function getDescriptionAttribute()
    {
        if (app()->getLocale()=='id')
            return $this->description_ind;
        return $this->description_eng;
    }

}
