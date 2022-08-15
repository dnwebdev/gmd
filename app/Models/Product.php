<?php

namespace App\Models;

use App\Scopes\OnlineProductScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class Product extends Model
{
    use SoftDeletes, SearchableTrait;
    public $primaryKey = 'id_product';
    protected $table = "tbl_product";
    protected $guarded = ['viewed', 'sold'];

    protected $searchable = [
        'columns'=>[
            'tbl_product.product_name' => 10,
            'tbl_company.company_name'=>6,
            'tbl_company.domain_memoria'=>6,

        ],
        'joins'=>[
            'tbl_company'=>['tbl_product.id_company','tbl_company.id_company']
        ]

    ];

    protected $casts = [
        'show_exclusion' => 'boolean',
        'vat' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OnlineProductScope);
    }

    public function order_detail()
    {
        return $this->belongsTo(OrderDetail::class, 'id_product', 'id_product');
    }
    public function order_details()
    {
        return $this->hasMany(OrderDetail::class, 'id_product', 'id_product');
    }

    public function addons()
    {
        return $this->belongsToMany(Addon::class, 'tbl_join_product_add', 'id_product', 'id_add');

    }

    public function image()
    {
        return $this->hasMany('\App\Models\ProductImage', 'id_product', 'id_product');
    }

    public function itineraryCollection()
    {
        return $this->hasMany('\App\Models\ProductItinerary', 'product_id', 'id_product');
    }

    public function city()
    {
        return $this->belongsTo('\App\Models\City', 'id_city', 'id_city');
    }

    public function category()
    {
        return $this->hasOne('\App\Models\ProductCategory', 'id_category', 'id_product_category');
    }

    public function product_type()
    {
        return $this->hasOne('\App\Models\ProductType', 'id_tipe_product', 'id_product_type');
    }

    public function mark()
    {
        return $this->hasOne('\App\Models\Mark', 'id_mark', 'id_mark');
    }

    public function tax()
    {
        return $this->hasMany('\App\Models\ProductTax', 'id_product', 'id_product');
    }

    public function tagValue()
    {
        return $this->hasMany(TagProductValue::class, 'product_id', 'id_product');
    }
    public function tags()
    {
        return $this->belongsToMany(TagProduct::class, 'tbl_tag_products_value','product_id','tag_product_id');
    }
    public function schedule()
    {
        return $this->hasMany('\App\Models\ProductSchedule', 'id_product', 'id_product');
    }

    public function first_schedule()
    {
        return $this->hasOne('\App\Models\ProductSchedule', 'id_product', 'id_product');
    }

    public function pricing()
    {
        return $this->hasMany('\App\Models\ProductPrice', 'id_product', 'id_product');
    }

    public function price()
    {
        return $this->hasOne(ProductPrice::class, 'id_product', 'id_product');
    }

    public function voucher()
    {
        return $this->belongsTo('\App\Models\Voucher', 'id_product', 'id_product');
    }

    public static function list_duration()
    {
        return [0 => \trans('product_provider.hours'), 1 => \trans('product_provider.days')];
    }

    public static function list_currency()
    {
        return ['IDR' => 'IDR'];
    }

    public static function list_booking_confirmation()
    {
        return [0 => 'Manually', 1 => 'Automatically'];
    }

    public static function list_status()
    {
        return [1 => \trans('product_provider.active'), 0 => \trans('product_provider.not_active')];
    }

    public static function list_amount_type()
    {
        return [0 => \trans('product_provider.fixed_amount'), 1 => \trans('product_provider.percentage')];
    }

    // public static function list_guest_type(){
    //     return [1=>'Adult',2=>'Children'];
    // }

    public static function list_display()
    {
        return [
            'Person' => 'Person',
            'Car' => 'Car',
            'Motorcycle' => 'Motorcycle',
            'Bike' => 'Bike',
            'Small Bus' => 'Small Bus',
            'Large Bus' => 'Large Bus',
            'Ship' => 'Ship',
            'Tent' => 'Tent',
        ];
    }

    public function getmainImageAttribute()
    {
        $img = $this->image()->orderBy('is_primary', 'desc')->first();
        if ($img) {
            if (\File::exists('uploads/products/thumbnail/' . $img->url)) {
                return $img->url;
            }
        }


        return 'img2.jpg';
    }

    public function orderCount()
    {
        if ($this->order_detail){
            return $this->order_details->invoice()->where('status','1')->count();
        }
        return 0;

    }

    public function getDurationTypeTextAttribute()
    {
        $value = $this->attributes['duration_type'];

        switch ($value) {
            case 0:
                return 'Hours';
                break;

            default:
                return 'Days';
                break;
        }
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'tbl_language_product', 'id_product', 'id_language');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'id_company','id_company');
    }

    public function fix_company()
    {
        return $this->belongsTo(Company::class, 'id_company','id_company')->where('status',1)
            ->whereNotNull('domain_memoria');
    }

    public function customSchema()
    {
        return $this->hasMany(CustomSchema::class, 'product_id');
    }

    public function ota()
    {
        return $this->belongsToMany(Ota::class, 'ota_products', 'product_id', 'ota_id')->withPivot('status');
    }

    public function scopeAvailableQuota($query)
    {
        return $query->has('schedule')
            ->where(function ($query) {
                return $query->where('quota_type', 'day')
                    ->orWhere('max_people', '>', function ($query) {
                        return $query->selectRaw('IFNULL(SUM(adult), 0)')
                            ->from(with(new OrderDetail)->getTable())
                            ->where('id_product', \DB::raw(self::getTable().'.id_product'))
                            ->whereIn('invoice_no', function ($query) {
                                return $query->select('invoice_no')
                                    ->from(with(new Order)->getTable())
                                    ->whereIn('status', [0, 1]);
                            });
                    });
            });
    }

    public function getUrlProductAttribute()
    {
        if ($this->company)
        return $this->company->domain_memoria.'/product/detail/'.$this->attributes['unique_code'];

        return null;
    }

    public function insurances()
    {
        return $this->belongsToMany(Insurance::class,'product_insurance','product_id','insurance_id');
    }

    public function sumOrder()
    {
        return $this->hasMany(OrderDetail::class, 'id_product')->join('tbl_order_header', 'tbl_order_detail.invoice_no', '=', 'tbl_order_header.invoice_no')->where('tbl_order_header.status', 1)->sum('total_amount');
    }
}
