<?php

namespace App\Http\Controllers\Backoffice\Home;

use App\Models\Company;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\WithdrawRequest;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatisticCtrl extends Controller
{
    /**
     * show view statistic page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('back-office.statistic.index');
    }

    /**
     * provides APi from data statistic
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadData()
    {
        $range = 'monthly';

        switch ($range) {
            case 'daily':
                $date = \Carbon\Carbon::now()->toDateString();
                break;
            case 'weekly':
                $date = \Carbon\Carbon::now()->subWeek()->toDateString();
                break;
            case 'monthly':
                $date = \Carbon\Carbon::now()->subMonth()->toDateString();
                break;
            case 'yearly':
                $date = \Carbon\Carbon::now()->subYear()->toDateString();
                break;
            default:
                $date = \Carbon\Carbon::now()->toDateString();
        }

        $data['total_provider'] = Company::count();
        $data['total_product'] = Product::count();

        $data['total_order'] = Order::where('created_at', '>', '2019-03-01 00:00:00')->whereHas('payment')->count();
        $data['total_non_cod_order'] = Order::where('created_at', '>', '2019-03-01 00:00:00')->whereHas('payment', function ($pay) {
            $pay->where('payment_gateway', '!=', 'Cash On Delivery');
        })->count();
        $data['total_cod_order'] = Order::where('created_at', '>', '2019-03-01 00:00:00')->whereHas('payment', function ($pay) {
            $pay->where('payment_gateway', '=', 'Cash On Delivery');
        })->count();

        $data['total_paid_order'] = Order::where('created_at', '>', '2019-03-01 00:00:00')->whereIn('status', [1, 2, 3, 4])->whereHas('payment')->count();

        $data['total_paid_non_cod_order'] = Order::where('created_at', '>', '2019-03-01 00:00:00')->whereIn('status', [1, 2, 3, 4])->whereHas('payment', function ($pay) {
            $pay->where('payment_gateway', '!=', 'Cash On Delivery');
        })->count();
        $data['total_paid_cod_order'] = Order::where('created_at', '>', '2019-03-01 00:00:00')->whereIn('status', [1, 2, 3, 4])->whereHas('payment', function ($pay) {
            $pay->where('payment_gateway', '=', 'Cash On Delivery');
        })->count();

        $data['nominal_order'] = 'IDR ' . number_format(Order::whereIn('status', [1, 2, 3, 4])->whereHas('payment', function ($pay) {
                $pay->where('payment_gateway', '!=', 'Redeem Voucher');
            })->where('tbl_order_header.created_at', '>', '2019-03-01 00:00:00')->sum('total_amount'), 0, '', '.');
        $data['nominal_non_cod_order'] = 'IDR ' . number_format(Order::whereIn('status', [1, 2, 3, 4])->whereHas('payment', function ($pay) {
                $pay->where('payment_gateway', '!=', 'Redeem Voucher')->where('payment_gateway', '!=', 'Cash On Delivery');
            })->where('tbl_order_header.created_at', '>', '2019-03-01 00:00:00')->sum('total_amount'), 0, '', '.');
        $data['nominal_cod_order'] = 'IDR ' . number_format(Order::whereIn('status', [1, 2, 3, 4])->whereHas('payment', function ($pay) {
                $pay->where('payment_gateway', '!=', 'Redeem Voucher')->where('payment_gateway', '=', 'Cash On Delivery');
            })->where('tbl_order_header.created_at', '>', '2019-03-01 00:00:00')->sum('total_amount'), 0, '', '.');

        $data['top_product'] = OrderDetail::selectRaw('tbl_order_detail.id_product,tbl_product.product_name,tbl_product.brief_description, tbl_company.company_name,tbl_company.domain_memoria, COUNT(tbl_order_detail.id_product) AS total')
            ->join('tbl_product', 'tbl_order_detail.id_product', '=', 'tbl_product.id_product')
            ->join('tbl_company', 'tbl_company.id_company', '=', 'tbl_product.id_company')
            ->join('tbl_order_header', 'tbl_order_detail.invoice_no', '=', 'tbl_order_header.invoice_no')
            ->join('tbl_payment', 'tbl_payment.invoice_no', '=', 'tbl_order_header.invoice_no')
            ->where('tbl_payment.payment_gateway', '!=', 'Cash On Delivery')
            ->where('tbl_product.booking_type', 'online')
            ->where('tbl_order_detail.created_at', '>', '2019-03-01 00:00:00')
            ->groupBy('tbl_order_detail.id_product')->orderBy('total', 'desc')->take(5)->get();

        $data['top_provider'] = OrderDetail::selectRaw('tbl_product.id_company,tbl_company.company_name,tbl_company.domain_memoria,tbl_company.short_description, COUNT(tbl_product.id_company) AS total')
            ->join('tbl_product', 'tbl_order_detail.id_product', '=', 'tbl_product.id_product')
            ->join('tbl_company', 'tbl_company.id_company', '=', 'tbl_product.id_company')
            ->join('tbl_order_header', 'tbl_order_detail.invoice_no', '=', 'tbl_order_header.invoice_no')
            ->join('tbl_payment', 'tbl_payment.invoice_no', '=', 'tbl_order_header.invoice_no')
            ->where('tbl_payment.payment_gateway', '!=', 'Cash On Delivery')
            ->where('tbl_order_detail.created_at', '>', '2019-03-01 00:00:00')
            ->groupBy('tbl_product.id_company')->orderBy('total', 'desc')->take(5)->get();

        $data['today_registered_provider'] = Company::whereDate('created_at', Carbon::now()->toDateString())->count();

        $data['today_created_product'] = Product::whereDate('created_at', Carbon::now()->toDateString())->count();

        $data['today_order_count'] = Order::whereIn('status', [1, 2, 3, 4])->whereHas('payment')->whereDate('created_at', Carbon::now()->toDateString())->count();
        $data['today_order_cod_count'] = Order::whereIn('status', [1, 2, 3, 4])->whereHas('payment', function ($pay) {
            $pay->where('payment_gateway', '=', 'Cash On Delivery');
        })->whereDate('created_at', Carbon::now()->toDateString())->count();
        $data['today_order_non_cod_count'] = Order::whereIn('status', [1, 2, 3, 4])->whereHas('payment', function ($pay) {
            $pay->where('payment_gateway', '!=', 'Cash On Delivery');
        })->whereDate('created_at', Carbon::now()->toDateString())->count();

        $data['today_order_nominal'] = 'IDR ' . number_format(Order::whereIn('status', [1, 2, 3, 4])->whereHas('payment')->whereDate('created_at', Carbon::now()->toDateString())->sum('total_amount'), 0, '', '.');
        $data['today_order_cod_nominal'] = 'IDR ' . number_format(Order::whereIn('status', [1, 2, 3, 4])->whereHas('payment', function ($pay) {
                $pay->where('payment_gateway', '=', 'Cash On Delivery');
            })->whereDate('created_at', Carbon::now()->toDateString())->sum('total_amount'), 0, '', '.');
        $data['today_order_non_cod_nominal'] = 'IDR ' . number_format(Order::whereIn('status', [1, 2, 3, 4])->whereHas('payment', function ($pay) {
                $pay->where('payment_gateway', '!=', 'Cash On Delivery');
            })->whereDate('created_at', Carbon::now()->toDateString())->sum('total_amount'), 0, '', '.');

        $data['today_order_pending_nominal'] = 'IDR ' . number_format(Order::whereDate('created_at', Carbon::now()->toDateString())->whereHas('payment', function ($pay) {
                $pay->where('payment_gateway', '!=', 'Cash On Delivery');
            })->whereIn('status', [0])->sum('total_amount'), 0, '', '.');

        $data['today_withdrawal'] = WithdrawRequest::whereDate('created_at', Carbon::now()->toDateString())->count();

        $data['total_withdrawal'] = WithdrawRequest::where('created_at', '>', '2019-03-01 00:00:00')->count();

        $data['withdrawal_complete'] = 'IDR ' . number_format(WithdrawRequest::where('created_at', '>', '2019-03-01 00:00:00')->whereIn('status', [1])->sum('amount'), 0, '', '.');

        $data['total_today_withdrawal_complete'] = WithdrawRequest::whereDate('created_at', Carbon::now()->toDateString())->whereIn('status', [1])->count();

        $data['latest_withdrawal_complete'] = WithdrawRequest::whereIn('status', [1])->has('company')->with('company')->latest()->take(5)->get();

        $data['today_withdrawal_complete'] = 'IDR ' . number_format(WithdrawRequest::whereDate('created_at', Carbon::now()->toDateString())->whereIn('status', [1])->sum('amount'), 0, '', '.');

        $data['latest_registered_provider'] = Company::latest()->take(5)->get();

        $data['latest_created_product'] = Product::has('company')->with('company')->latest()->take(5)->get();

        $data['latest_order'] = Order::with('company')->where('status', '1')->whereHas('payment', function ($pay) {
            $pay->where('payment_gateway', '!=', 'Cash On Delivery');
        })->latest()->take(5)->get();

        $data['latest_pending_order'] = Order::with('company')
            ->where('status', 0)
            ->whereHas('payment', function ($pay) {
                $pay->where('payment_gateway', '!=', 'Cash On Delivery');
            })
            ->latest()->take(5)->get();

        $data['updated_at'] = strtotime(Carbon::now()->toDateTimeString());

        $data['active_providers'] = DB::table('tbl_login_log as l')->selectRaw('c.company_name, c.domain_memoria , COUNT(l.company_id) as total')
            ->join('tbl_company as c', 'c.id_company', '=', 'l.company_id')
            ->groupBy('l.company_id')
            ->orderBy('total', 'desc')
            ->where('last_login', '>=', $date)
            ->having('total', '>', '0')
            ->get();
        return response()->json($data);
    }
}
