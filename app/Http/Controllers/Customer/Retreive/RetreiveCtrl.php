<?php

namespace App\Http\Controllers\Customer\Retreive;

use App\Models\CategoryPayment;
use App\Models\ListPayment;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class RetreiveCtrl extends Controller
{
    /**
     * RetreiveCtrl constructor.
     */
    public function __construct()
    {
        $this->middleware('company');
    }

    /**
     * show search booking
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function retreive(Request $request)
    {
        $company = \App\Models\Company::find($request->get('my_company'));
        $orderAds = null;
        if ($company) {
            if ($request->get('klhk')){
                return view('klhk.customer.retreive.index', ['company' => $company, 'orderAds' => $orderAds]);
            }
            return view('customer.retreive.index', ['company' => $company, 'orderAds' => $orderAds]);
        }

        return redirect('/');

    }

    /**
     * retrieve data booking
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function getDataRetreive(Request $request)
    {
        $this->validate($request, [
            'no_invoice' => [
                'required',
                Rule::exists('tbl_order_header', 'invoice_no')->where('id_company', $request->get('my_company'))
            ]
        ]);
        $order = Order::where('invoice_no', $request->get('no_invoice'))->where('id_company',
            $request->get('my_company'))->first();

        $company = \App\Models\Company::find($request->get('my_company'));
        switch($order->payment->payment_gateway) {
            case 'Xendit Virtual Account':
                $code_payment = 'virtual_account';
                break;
            case 'Xendit Credit Card':
                $code_payment = 'credit_card';
                break;
            case 'Xendit Virtual Account OVO':
                $code_payment = 'ovo';
                break;
            case 'Xendit Alfamart':
                $code_payment = 'alfamart';
                break;
            case 'Xendit DANA':
                $code_payment = 'dana';
                break;
            case 'Xendit OVO':
                $code_payment = 'ovo_live';
                break;
            case 'Xendit LinkAja':
                $code_payment = 'linkaja';
                break;
            case 'Midtrans Indomaret':
                $code_payment = 'indomaret';
                break;
            case 'Midtrans Gopay':
                $code_payment = 'gopay';
                break;
            case 'Xendit Kredivo':
                $code_payment = 'kredivo';
                break;
            case 'Midtrans Virtual Account BCA':
                $code_payment = 'bca_va';
                break;
            case 'Midtrans AkuLaku':
                $code_payment = 'akulaku';
                break;
            case 'Manual Transfer BCA':
                $code_payment = 'bca_manual';
                break;
            case 'Cash On Delivery':
                $code_payment = 'cod';
                break;
            default:
                $code_payment = 'redeem';
                break;
        }
        $payment = $company->payments()->where('code_payment', $code_payment)->first();
        $payment_list = CategoryPayment::with([
            'listPayments' => function ($q) {
                $q->whereStatus(1)->orderBy('name_payment', 'asc');
            }
        ])->orderBy('name_third_party', 'desc')->get();

        $orderAds = null;
        if ($order && $company) {
            $product = $order->order_detail->product;
            if ($order->booking_type == 'offline') {
                if ($order->payment) {
                    if ($order->payment->payment_gateway =='Redeem Voucher'){
                        if ($request->get('klhk')){
                            return view('klhk.customer.retreive.data', compact('order', 'company', 'product', 'orderAds', 'payment_list','payment'));
                        }
                        return view('customer.retreive.data', compact('order', 'company', 'product', 'orderAds', 'payment_list','payment'));
                    }
                    if ($order->payment->expiry_date < Carbon::now()->toDateTimeString()) {
                         if ($request->get('klhk')){
                            return view('klhk.customer.retreive.data', compact('order', 'company', 'product', 'orderAds', 'payment_list','payment'));
                        }
                        return view('customer.retreive.data',
                            compact('order', 'company', 'product', 'orderAds', 'payment_list','payment'));
                    }
                }

                $states = \App\Models\State::select('id_state', 'state_name', 'state_name_en')
                    ->where('id_country', 102)
                    ->orderBy('state_name'.(app()->getLocale() == 'en' ? '_en' : ''), 'asc')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'id_state' => $item->id_state,
                            'state_name' => $item->{'state_name'.(app()->getLocale() == 'en' ? '_en' : '')},
                        ];
                    });

                if ($request->get('klhk')){
                    return view('klhk.customer.products.offline_book', compact('company', 'product', 'order', 'orderAds', 'payment_list','payment', 'states'));
                }
                return view('customer.products.offline_book', compact('company', 'product', 'order', 'orderAds', 'payment_list','payment', 'states'));
            }
            if ($request->get('klhk')){
                return view('klhk.customer.retreive.data', compact('order', 'company', 'product', 'orderAds', 'payment_list','payment'));
            }
            return view('customer.retreive.data', compact('order', 'company', 'product', 'orderAds', 'payment_list','payment'));
        }
        msg("We Can not find the order", 2);
        return redirect('/');
    }

    /**
     * success payment
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function successPayment(Request $request)
    {
        $data['order'] = Order::where('invoice_no', $request->get('no_invoice'))->where('id_company',
            $request->get('my_company'))->first();
        $data['orderAds'] = null;
        if (!$data['order']) {
            abort(404);
        }
        $data['company'] = \App\Models\Company::find($request->get('my_company'));
        $data['relateds'] = $data['company']->products()->where('publish', true)->where('status',
        true)->availableQuota()->whereDoesntHave('order_detail', function ($od) use ($data) {
            $od->where('invoice_no', $data['order']->invoice_no);
        })->inRandomOrder()->take(3)->get();
        if (in_array(optional($data['order']->customer_manual_transfer)->status, ['need_confirmed', 'customer_reupload'])) {
            if ($request->get('klhk')) {
                return view('klhk.customer.products.payment.success-needconfirmed', $data);
            }
            return view('customer.products.payment.success-needconfirmed', $data);
        }
        if ($data['order']->payment && $data['order']->payment->payment_gateway == 'Cash On Delivery') {
            if ($request->get('klhk')){
                return view('klhk.customer.products.payment.cod-success', $data);
            }
            return view('customer.products.payment.cod-success', $data);
        }
        if ($data['order']->status != 1) {
            if ($request->get('klhk')){
                return redirect()->route('memoria.retrieve.data', ['no_invoice' => $data['order']->invoice_no]);
            }

            return redirect()->route('memoria.retrieve.data', ['no_invoice' => $data['order']->invoice_no]);
        }

        if ($request->get('klhk')){
            return view('klhk.customer.products.payment.success', $data);
        }

        return view('customer.products.payment.success', $data);
    }
}
