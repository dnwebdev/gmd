<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoPage extends Model
{
    public $incrementing = true;
    protected $guarded = [];
    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(PageCategory::class,'category_page_id');
    }
}
