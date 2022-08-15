<?php

namespace App\Models;

use App\Scopes\ActiveProviderScope;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'tbl_order_header';
    protected $primaryKey = 'invoice_no';
    public $incrementing = false;

    protected $guarded = ['is_void'];
    protected $appends = ['total_amount_text','status_text'];
    protected $casts = [
        'invoice_detail' => 'json',
        'is_checked'=>'boolean'
    ];

    protected static function boot()
    {
        parent::boot();
        static::updated(function ($model) {
            if ($model->isDirty('status') && $model->payment) {
                switch ($model->status) {
                    case '0':
                        $model->payment->update(['status' => 'PENDING']);
                        break;
                    case '6':
                    case '7':
                    case '5':
                        $model->payment->update(['status' => 'CANCELLED']);
                        break;
                }
            }

        });
    }

    public function customer()
    {
        return $this->hasOne('App\Models\Customer', 'id_customer', 'id_customer');
    }

    public function voucher()
    {
        return $this->belongsTo('App\Models\Voucher', 'id_voucher', 'id_voucher');
    }

    public function voucherGomodo()
    {
        return $this->belongsTo('App\Models\Voucher', 'id_voucher', 'id_voucher')->where('by_gomodo', 1);
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'id_company',
            'id_company')->withoutGlobalScope(ActiveProviderScope::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return date('M d, Y H:i', strtotime($value));
    }

    public function customer_info()
    {
        return $this->hasOne('\App\Models\OrderCustomer', 'invoice_no', 'invoice_no');
    }

    public function customer_manual_transfer()
    {
        return $this->hasOne(CustomerManualTransfer::class, 'invoice_no');
    }

    public static function listManualTransfer(){
        return [
            'customer_reupload' => \trans('order_provider.status_manualtransfer.customer_reupload'),
            'need_confirmed' => \trans('order_provider.status_manualtransfer.need_confirmed'),
            'rejected' => \trans('order_provider.status_manualtransfer.rejected'),
            'rejected_reupload' => \trans('order_provider.status_manualtransfer.rejected_reupload'),
            'accept' => \trans('order_provider.status_manualtransfer.accept')
        ];
    }
    public static function changeManualTransfer(){
        return [
            'rejected' => \trans('order_provider.status_manualtransfer.rejected'),
            'rejected_reupload' => \trans('order_provider.status_manualtransfer.rejected_reupload'),
            'accept' => \trans('order_provider.status_manualtransfer.accept')
        ];
    }

    public function order_detail()
    {
        return $this->hasOne('\App\Models\OrderDetail', 'invoice_no', 'invoice_no');
    }

    public function extra()
    {
        return $this->hasMany('\App\Models\OrderExtraItem', 'invoice_no', 'invoice_no');
    }

    public static function list_status()
    {

        return [
            8 => 'Unconfirm',
            0 => \trans('order_provider.not_paid'),
            1 => \trans('order_provider.paid'),
            2 => 'Processed',
            3 => 'On Shipping',
            4 => 'Complete',
            5 => 'Cancel by User',
            6 => \trans('order_provider.cancel_vendor'),
            7 => \trans('order_provider.cancel_system'),
        ];
    }

    public function guides()
    {
        return $this->hasMany(GuideInformation::class, 'invoice_no');
    }

    public function getStatusTextAttribute()
    {
        $value = $this->attributes['status'];
//        $is_void = $this->attributes['is_void'];
//        $allow_payment = $this->attributes['allow_payment'];
//
        $list_status = $this->list_status();
//        if ($value == 0 && !$allow_payment) {
//            return 'Need Confirmation';
//        } elseif ($is_void) {
//            return 'Void';
//        }

        return $list_status[$value] ?? 'Unknown';
    }

    public function getTotalAmountTextAttribute()
    {
        if ($this->currency) {
            return format_priceID($this->attributes['total_amount'], $this->currency->currency);
        }

    }

    public function getCurrencyAttribute()
    {
        $value = $this->order_detail()->first();
        return $value;
    }

    public function payment()
    {
        return $this->hasOne('\App\Models\Payment', 'invoice_no', 'invoice_no');
    }

    public function scopeWherePaid($query, bool $paid = true)
    {
        if ($paid) {
            return $query->whereIn('status', [1, 2, 3, 4]);
        } else {
            return $query->whereNotIn('status', [1, 2, 3, 4]);
        }
    }

    public function scopeWhereCod($query, bool $cod = true)
    {
        return $query->whereHas('payment', function ($q) use ($cod) {
            $q->where('payment_gateway', ($cod ? '=' : '!='), 'Cash On Delivery');
        });
    }

    public function scopeDateRange($query, $range = null, $n = 1)
    {
        return $query->when($range, function ($query, $range) use ($n) {
            switch ($range) {
                case 'today':
                    return $query->whereDate('created_at', today()->toDateString());
                    break;
                case 'day':
                    return $query->whereDate('created_at', '>=', today()->subDays($n)->toDateString());
                    break;
                case 'week':
                    return $query->whereDate('created_at', '>=', today()->subWeeks($n)->toDateString());
                    break;
                case 'month':
                    return $query->whereDate('created_at', '>=', today()->subMonths($n)->toDateString());
                    break;
                case 'year':
                    return $query->whereDate('created_at', '>=', today()->subYears($n)->toDateString());
                    break;

            }
        });
    }

    public function additional_orders()
    {
        return $this->hasMany(AdditionalOrder::class,'invoice_no');
    }


}
