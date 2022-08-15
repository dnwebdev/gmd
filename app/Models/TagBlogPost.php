<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagBlogPost extends Model
{
    protected $table = "join_tag_blogs";

    public function tagBlogPost()
    {
    	return $this->hasOne(TagBlog::class, 'id','tag_blog_id');
    }
}
