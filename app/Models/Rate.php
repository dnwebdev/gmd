<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $table = 'tbl_rate';
    protected $primaryKey = 'id_rate';
    protected $guarded = ['id_rate'];
}
