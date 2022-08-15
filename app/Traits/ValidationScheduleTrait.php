<?php
/**
 * Created by PhpStorm.
 * User: hkari
 * Date: 2/17/2019
 * Time: 2:23 PM
 */

namespace App\Traits;

use App\Models\OrderDetail;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

trait ValidationScheduleTrait
{
    /**
     * function to get Valid schedule
     * @param $code
     * @param $schedule_date
     * @param $id_company
     * @param $pax
     * @return array
     */
    public function scheduleValidation($code, $schedule_date, $id_company, $pax)
    {
        $can_book = false;
        $product = Product::where('unique_code', $code)->where('id_company', $id_company)
            ->where('status', 1)
            ->with(['first_schedule' => function ($query) use ($schedule_date) {
                    return $query->whereDate('start_date', '<=', $schedule_date)
                        ->whereDate('end_date', '>=',  $schedule_date);
                }])
            ->first();


        if (!$product) {
            return [
                'can_book'=>$can_book,
                'status' => 404,
                'result' => [
                    'message' => 'Product is not valid'
                ]

            ];
        }
        if ($product->availability == '1') {
            $schedule = $product->schedule()
                ->where('start_date', '<=', $schedule_date)
                ->where('end_date', '>=', $schedule_date)->first();
        } else {
            $schedule = $product->schedule()
                ->where('start_date', '<=', $schedule_date)
                ->first();
        }
        if (!$schedule) {
            return [
                'can_book'=>$can_book,
                'status' => 404,
                'result' => [
                    'message' => 'Schedule Date is not valid'
                ]

            ];
        }

        // Limit max order unit
        if (!empty($product->max_order) && $pax > $product->max_order) {
            return [
                'can_book'=>$can_book,
                'status' => 403,
                'result' => [
                    'message' => trans('booking.validation.max_order', ['max' => $product->max_order])
                ]

            ];
        }


        $orderDetails = OrderDetail::whereHas('product', function ($p) use ($product) {
            $p->where('id_product', $product->id_product);
        });

        $isTypeDay = ($product->quota_type == 'day');
        $totalBooks = $orderDetails->whereHas('invoice', function ($invoice) use ($id_company) {
            $invoice->where('id_company', $id_company);
            $invoice->whereIn('status', [0, 1, 2, 3]);
        })
            ->where('id_schedule', $product->first_schedule->id_schedule)
            ->when($isTypeDay, function ($query) use ($schedule_date) {
                return $query->whereDate('schedule_date', $schedule_date);
            })
            ->sum('adult');
        $maxPaxOrder = $product->max_people ?? 999;
        if (($totalBooks + (int)$pax) > $maxPaxOrder) {
            $message = 'Schedule is full';

            if ($isTypeDay && $totalBooks >= $maxPaxOrder) {
                $message = trans('booking.validation.empty_schedule_date', ['date' => $schedule_date]);
            } elseif ($isTypeDay && $totalBooks < $maxPaxOrder) {
                $message = trans('booking.validation.limited_slot', ['slot' => ($maxPaxOrder - $totalBooks), 'date' => $schedule_date]);
            }
            return [
                'can_book'=>$can_book,
                'status' => 403,
                'result' => [
                    'message' => $message
                ]

            ];
        }
        $minimumNotice = Carbon::now()->addDay($product->minimum_notice)->toDateString();
        if ($minimumNotice > $schedule_date) {
            return [
                'can_book'=>$can_book,
                'status' => 403,
                'result' => [
                    'message' => 'Minimum Notice is ' . $product->minimum_notice . ' day'
                ]

            ];
        }
        $price = $product->pricing()->where('price_from', '<=', $pax)->where('price_until', '>=', $pax)->orderBy('price', 'asc')->first();
        if (!$price) {
            $price = $product->pricing()->orderBy('price', 'desc')->first();
        }
        $companyDiscount = 0;
        if ($product->discount_amount) {
            if ($product->discount_amount_type == '1') {
                $companyDiscount = ($product->discount_amount / 100) * (int)($pax * $price->price);
            } else {
                $companyDiscount = $product->discount_amount * (int)$pax;
            }
        }
        if ($companyDiscount >= (int)($pax * $price->price)) {
            $totalPrice = 0;
            $isFree = true;
        } else {
            $totalPrice = (int)($pax * $price->price) - $companyDiscount;
            $isFree = false;
        }
        $can_book=true;
        return [
            'status' => 200,
            'result' => [
                'message' => 'OK',
                'result' => [
                    'can_book'=>$can_book,
                    'min_notice' => $product->minimum_notice,
                    'min_notice_date' => $minimumNotice,
                    'schedule'=>$schedule,
                    'is_free' => $isFree,
                    'booked' => $totalBooks,
                    'stock' => $maxPaxOrder - $totalBooks,
                    'pax'=>$pax,
                    'price' => (int)$price->price,
                    'priceText' => $product->currency . ' ' .  number_format((int)$price->price,0,'','.'),
                    'total_price' => (int)($pax * $price->price),
                    'discount_label'=>app()->getLocale()=='id'?'Diskon':'Discount',
                    'company_discount_name' => $product->discount_name??'Discount',
                    'company_discount_price' => $companyDiscount,
                    'company_discount_price_text' => $product->currency . ' ' . number_format($companyDiscount, 0, '', '.'),
                    'price_display' => $price->unit->name,
                    'total_price_text' => $product->currency . ' ' . number_format((int)($pax * $price->price), '0', '', '.'),
                    'grand_total' => $totalPrice,
                    'grand_total_text' => $product->currency . ' ' . number_format($totalPrice, '0', '', '.')
                ]
            ]
        ];
    }
}
