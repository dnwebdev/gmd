<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageCategory extends Model
{
    protected $table = 'category_pages';
    public $incrementing = true;
    public $timestamps = false;

    public function seos()
    {
        return $this->hasMany(SeoPage::class,'category_page_id');
    }

}
