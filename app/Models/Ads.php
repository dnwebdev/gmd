<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    protected $table = 'tbl_ads';
    protected $primaryKey = 'id';
    protected $guard = ['id'];
    protected $guarded = [];

    public static $languages = [
        'id'    => [
            'id'    => 'Indonesia',
            'en'    => 'Indonesian'
        ],
        'en'    => [
            'id'    => 'Inggris',
            'en'    => 'English'
        ]
    ];

    public static $purpose = [
        'brand_awareness'   => 'Kesadaran Merek',
        'traffic'           => 'Jumlah Pengunjung'
    ];

    public static $gender = [
        'all'       => 'Semua jenis kelamin',
        'men'       => 'Laki - Laki',
        'women'     => 'Perempuan'
    ];

    public static $age = [
        '12-20'     => '12 - 20 Tahun',
        '21-40'     => '21 - 40 Tahun',
        '40-65'     => '40 - 65 Tahun'
    ];

    public static $call_button = [
        'Hubungi kami',
        'Pelajari lebih lanjut',
        'Lihat jadwal',
        'Pesan sekarang',
        'Daftar sekarang'
    ];

    protected $casts = [
        'language' => 'array',
    ];

    public function getPurposeAttribute($value)
    {
        if (isset(self::$purpose[$value])) {
            return self::$purpose[$value];
        }

        return $value;
    }

    public function getGenderAttribute($value)
    {
        if (isset(self::$gender[$value])) {
            return self::$gender[$value];
        }

        return $value;
    }

    public function getAgeAttribute($value)
    {
        if (isset(self::$age[$value])) {
            return self::$age[$value];
        }

        return $value;
    }

    public function getCallButtonAttribute($value)
    {
        if (isset(self::$call_button[$value])) {
            return self::$call_button[$value];
        }

        return $value;
    }


    public function paymentMethod()
    {
        return $this->belongsTo('App\Models\PaymentMethod', 'payment_method_id', 'id');
    }

    public function businessCategory()
    {
        return $this->belongsToMany(BusinessCategory::class, 'ads_business_categories', 'ads_id', 'business_category_id');
    }

    public function adsCity()
    {
        return $this->belongsToMany('App\Models\City', 'tbl_ads_city', 'ads_id', 'city_id');
    }

    public function order_ads()
    {
        return $this->hasOne('App\Models\OrderAds', 'ads_id');
    }
    public function ads_order()
    {
        return $this->belongsTo('App\Models\OrderAds', 'ads_id');
    }

    public function company(){
    	return $this->belongsTo('\App\Models\Company','company_id');
    }

    public function getImageUrlAttribute()
    {
        return asset('storage/image-ads/'. $this->document_ads);
    }

}
