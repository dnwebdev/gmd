<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;

class OrderAds extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'tbl_order_ads';
    public $incrementing = false;
    protected $casts = [
        'response'=>'array'
      ];
    protected $fillable = ['category_ads','fee_credit_card','promo_code_id','voucher_cashback_id','no_invoice','reference_number','expiry_date','invoice_url','gxp_amount','voucher','amount','status','response','ads_id','total_price','payment_gateway','status_payment','amount_payment'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }

    public function adsOrder()
    {
        return $this->belongsTo('App\Models\Ads', 'ads_id', 'id');
    }

    public function promoAds()
    {
        return $this->belongsTo('App\Models\PromoCode', 'promo_code_id', 'id');
    }

    public function voucherAds()
    {
        return $this->belongsTo('App\Models\CashBackVoucher', 'voucher_cashback_id', 'id');
    }
}
