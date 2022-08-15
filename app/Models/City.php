<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'tbl_city';
    protected $primaryKey = 'id_city';
    protected $guarded = [];
    public $timestamps = false;

    public function state()
    {
        return $this->belongsTo('App\Models\State', 'id_state', 'id_state');
    }

    public function customer_information()
    {
        return $this->belongsTo('\App\Models\OrderCustomer', 'id_city', 'id_city');
    }

    public function company()
    {
        return $this->hasMany('\App\Models\Company', 'id_city', 'id_city');
    }

    public function product()
    {
        return $this->hasOne('\App\Models\Product', 'id_city', 'id_city')->withTrashed();
    }
    public function products()
    {
        return $this->hasMany('\App\Models\Product', 'id_city', 'id_city');
    }
    /**
     * Fallback nama city name en jika kosong, maka fallback ke bahasa indonesia
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function getCityNameEnAttribute($value)
    {
        if (is_null($value)) {
            return $this->city_name;
        }

        return $value;
    }

}
