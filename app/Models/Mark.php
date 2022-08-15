<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    protected $table = "tbl_mark";
    protected $primaryKey ='id_mark';

    protected $fillable = ['mark','id_company'];
}
