<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeFinance extends Model
{
    protected $table = 'type_finance';

    public function categoryType()
    {
        return $this->belongsTo(CategoryFinance::class, 'category_finance_id');
    }
}
