<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'tbl_state';
    protected $primaryKey = 'id_state';
    protected $guarded = [];
    protected $appends = ['state_image_url'];
    public $timestamps = false;

    public function city(){
    	return $this->hasMany('\App\Models\City','id_state','id_state');
    }

    public function country(){
    	return $this->belongsTo('\App\Models\Country','id_country','id_country');
    }

    /**
     * Fallback nama state name en jika kosong, maka fallback ke bahasa indonesia
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function getStateNameEnAttribute($value)
    {
        if (is_null($value)) {
            return $this->state_name;
        }

        return $value;
    }

    public function getStateImageUrlAttribute()
    {
        if (\File::exists(public_path($this->attributes['state_image']))){

            return $this->attributes['state_image'];
        }
        return 'http://placehold.it/200x100';
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class,City::class,'id_state','id_city','id_state','id_city');
    }

}
