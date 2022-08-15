<?php

namespace App\Http\Controllers\Company\Ads;

use App\Traits\DiscordTrait;
use PDF;
use Mail;
use App\User;
use Carbon\Carbon;
use App\Models\Ads;
use App\Models\OrderAds;
use App\Traits\GxpTrait;
use App\Models\PromoCode;
use App\Models\JournalGXP;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\CashBackVoucher;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use App\Http\Requests\AdsRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\GoogleAdsRequest;
use App\Models\City;
use App\Models\BusinessCategory;

class AdsCtrl extends Controller
{
    use GxpTrait;
    use DiscordTrait;

    // var $company = 0;

    // Api key xendit
    private $base_url = "https://api.xendit.co/v2/";
    private $xendit_key;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        // Declaration  authentication
        $this->middleware('auth');
        $this->xendit_key = env('XENDIT_KEY');
    }

    /**
     * Function initalize get data user
     *
     * @param mixed $request
     *
     * @return void
     */
    private function initalize(Request $request)
    {
        $user = \Auth::user();
        $this->user = $user->id_user_agen;
        $this->company = $user->id_company;
    }

    /**
     * loadData
     * Function load datatables company premium index
     *
     * @return void
     */
    private function loadData()
    {
        $company = auth('web')->user()->company;
        $ads = Ads::with(['order_ads', 'company'])->where('company_id', $company->id_company);
        return DataTables::of($ads)
            ->editColumn('created_at', function ($data) {
                return [
                    'display' => $data->created_at->format('d/m/Y'),
                    'timestamp' => $data->created_at->timestamp
                ];
            })
            ->addColumn('no_invoice', function ($data) {
                if ($data->order_ads) {
                    return $data->order_ads->no_invoice;
                }
                return '-';
            })
            ->addColumn('category', function ($data) {
                if ($data->order_ads) {
                    $categories =  explode('&', str_replace(' ads', '', strtolower($data->order_ads->category_ads)));
                    return collect($categories)->map(function ($value) {
                        return trans('premium.ads_category.'.trim($value));
                    })->implode(' & ');
                }
                return '-';
            })
            ->editColumn('date', function ($data) {
                return Carbon::parse($data->start_date)->format('d/m/Y') . ' - ' . Carbon::parse($data->end_date)->format('d/m/Y');
            })
            ->addColumn('duration', function ($data) {
                return Carbon::parse($data->end_date)->diffInDays(Carbon::parse($data->start_date)) . ' ' . \trans('premium.validation.day');
            })
            ->addColumn('action', function ($data) {
                // if ($data->order_ads){
                //     return $data->order_ads->status;
                // }
                // return '-';
                $voucherAmount = null;
                $gxp_amount = null;
                if ($data->order_ads->voucherAds) {
                    $voucherAmount = $data->order_ads->voucherAds->nominal;
                } elseif ($data->order_ads->voucher) {
                    $voucherAmount = $data->order_ads->voucher;
                }
                if ($data->order_ads->gxp_amount) {
                    $gxp_amount = $data->order_ads->gxp_amount;
                }
                if ($data->order_ads->status == 0) {
                    $html = '<span class="btn btn-danger btn-status" style="background: #fe6969;">' . \trans('premium.validation.unpaid') . '</span> <a href="" class="unpaid" data-toggle="modal" data-target=".modal-unpaid-ads" 
                        data-id="' . $data->id . '"
                        data-username="' . $data->company->agent->first_name . '"
                        data-company="' . $data->company->company_name . '"
                        data-company_email="' . $data->company->agent->email . '"
                        data-phone_company="' . $data->company->agent->phone . '"
                        data-no_invoice="' . $data->order_ads->no_invoice . '"
                        data-category_ads="' . $data->order_ads->category_ads . '"
                        data-created_at="' . $data->created_at->format('d M Y') . '"
                        data-method_payment="' . $data->order_ads->payment_gateway . '"
                        data-date="' . date('d M Y', strtotime($data->start_date)) . ' - ' . date('d M Y',
                            strtotime($data->end_date)) . '"
                        data-min_budget="' . number_format($data->min_budget, 0) . '"
                        data-url="' . $data->url . '"
                        data-amount="' . number_format($data->order_ads->amount, 0) . '"
                        data-service_fee="' . number_format($data->service_fee, 0) . '"
                        data-promo_voucher="' . number_format($voucherAmount, 0) . '"
                        data-gxp_amount="' . number_format($gxp_amount, 0) . '"
                        data-total_price="' . number_format($data->order_ads->total_price, 0) . '"
                        data-fee_credit_card="' . number_format($data->order_ads->fee_credit_card, 0) . '"
                    > <i class="fa fa-eye"></i> <small>Detail</small></a>';
                    return $html;
                } elseif ($data->order_ads->status == 1) {
                    $html = '<span class="btn btn-success btn-status" style="background: #1dcd69;">' . \trans('premium.validation.paid') . '</span> <a href="" id="paid" data-toggle="modal" data-target=".modal-paid-ads" 
                        data-id="' . $data->id . '"
                        data-username="' . $data->company->agent->first_name . '"
                        data-company="' . $data->company->company_name . '"
                        data-company_email="' . $data->company->agent->email . '"
                        data-phone_company="' . $data->company->agent->phone . '"
                        data-no_invoice="' . $data->order_ads->no_invoice . '"
                        data-category_ads="' . $data->order_ads->category_ads . '"
                        data-created_at="' . $data->created_at->format('d M Y') . '"
                        data-method_payment="' . $data->order_ads->payment_gateway . '"
                        data-date="' . date('d M Y', strtotime($data->start_date)) . ' - ' . date('d M Y',
                            strtotime($data->end_date)) . '"
                        data-min_budget="' . number_format($data->min_budget, 0) . '"
                        data-url="' . $data->url . '"
                        data-amount="' . number_format($data->order_ads->amount, 0) . '"
                        data-service_fee="' . number_format($data->service_fee, 0) . '"
                        data-promo_amount="' . number_format($data->order_ads->voucher, 0) . '"
                        data-gxp_amount="' . number_format($voucherAmount, 0) . '"
                        data-total_price="' . number_format($data->order_ads->total_price, 0) . '"
                        data-fee_credit_card="' . number_format($data->order_ads->fee_credit_card, 0) . '"
                    > <i class="fa fa-eye"></i> <small>Detail</small>';
                    return $html;
                } elseif ($data->order_ads->status == 2) {
                    $html = '<span  class="btn btn-info btn-status" style="background: #69b1ef;">' . \trans('premium.validation.active') . '</span> <a href="" id="active" data-toggle="modal" data-target=".modal-active-ads" 
                        data-id="' . $data->id . '"
                        data-username="' . $data->company->agent->first_name . '"
                        data-company="' . $data->company->company_name . '"
                        data-company_email="' . $data->company->agent->email . '"
                        data-phone_company="' . $data->company->agent->phone . '"
                        data-no_invoice="' . $data->order_ads->no_invoice . '"
                        data-category_ads="' . $data->order_ads->category_ads . '"
                        data-created_at="' . $data->created_at->format('d M Y') . '"
                        data-method_payment="' . $data->order_ads->payment_gateway . '"
                        data-date="' . date('d M Y', strtotime($data->start_date)) . ' - ' . date('d M Y',
                            strtotime($data->end_date)) . '"
                        data-min_budget="' . number_format($data->min_budget, 0) . '"
                        data-url="' . $data->url . '"
                        data-amount="' . number_format($data->order_ads->amount, 0) . '"
                        data-service_fee="' . number_format($data->service_fee, 0) . '"
                        data-promo_amount="' . number_format($data->order_ads->voucher, 0) . '"
                        data-gxp_amount="' . number_format($voucherAmount, 0) . '"
                        data-total_price="' . number_format($data->order_ads->total_price, 0) . '"
                        data-fee_credit_card="' . number_format($data->order_ads->fee_credit_card, 0) . '"
                    > <i class="fa fa-eye"></i> <small>Detail</small>';
                    return $html;
                } elseif ($data->order_ads->status == 3) {
                    $html = '<span class="btn btn-primary btn-status">' . \trans('premium.validation.inactive') . '</span> <a href="" id="inactive" data-toggle="modal" data-target=".modal-inactive-ads"
                        data-id="' . $data->id . '"
                        data-username="' . $data->company->agent->first_name . '"
                        data-company="' . $data->company->company_name . '"
                        data-company_email="' . $data->company->agent->email . '"
                        data-phone_company="' . $data->company->agent->phone . '"
                        data-no_invoice="' . $data->order_ads->no_invoice . '"
                        data-category_ads="' . $data->order_ads->category_ads . '"
                        data-created_at="' . $data->created_at->format('d M Y') . '"
                        data-method_payment="' . $data->order_ads->payment_gateway . '"
                        data-date="' . date('d M Y', strtotime($data->start_date)) . ' - ' . date('d M Y',
                            strtotime($data->end_date)) . '"
                        data-min_budget="' . number_format($data->min_budget, 0) . '"
                        data-url="' . $data->url . '"
                        data-amount="' . number_format($data->order_ads->amount, 0) . '"
                        data-service_fee="' . number_format($data->service_fee, 0) . '"
                        data-promo_amount="' . number_format($data->order_ads->voucher, 0) . '"
                        data-gxp_amount="' . number_format($voucherAmount, 0) . '"
                        data-total_price="' . number_format($data->order_ads->total_price, 0) . '"
                        data-fee_credit_card="' . number_format($data->order_ads->fee_credit_card, 0) . '"
                    > <i class="fa fa-eye"></i> <small>Detail</small></a>';
                    return $html;
                } else {
                    $html = '<span class="btn btn-danger btn-status" style="background: red;">' . \trans('premium.validation.cancel_by_system') . '</span>';
                    return $html;
                }
                return '-';
            })
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     * function load data index
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        config(['jsvalidation.disable_remote_validation' => true]);
        $company = auth('web')->user()->company;
        if ($request->isXmlHttpRequest()) {
            return $this->loadData($request);
        }
        $ads = Ads::with('order_ads')->where('company_id', $company->id_company)->get();
        $payment_method = PaymentMethod::all();
        $cash_back = \App\Models\Company::with([
            'cash_backs' => function ($cashback) {
                $cashback->whereStatus('active')->where('expired_at', '>=', Carbon::now()->toDateTimeString());
            }
        ])->find($company->id_company);
        $gxp_sum = $this->mygxp();
        $country = \App\Models\Country::select('id_country', 'country_name')->orderBy('country_name')->get();
        $business_categories = \App\Models\BusinessCategory::select('id', 'business_category_name',
            'business_category_name_id')
            ->orderBy('business_category_name' . (app()->getLocale() == 'id' ? '_id' : ''))
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => app()->getLocale() == 'id' ? $item->business_category_name_id : $item->business_category_name
                ];
            });
        toastr();

        if (auth()->user()->company->is_klhk == 1) {
            return view('klhk.dashboard.company.ads.index', compact(
                'payment_method', 'ads', 'cash_back', 'company', 'gxp_sum', 'country', 'business_categories'
            ));
        }
        return view('dashboard.company.ads.index', compact(
            'payment_method', 'ads', 'cash_back', 'company', 'gxp_sum', 'country', 'business_categories'
        ));
    }

    /**
     * function check myvoucher code
     *
     * @param mixed $request
     *
     * @return void
     */
    public function myvoucher(Request $request)
    {
        $this->initalize($request);
        $rule = [
            'id' => [
                'required',
                Rule::exists('tbl_voucher_cashback', 'id')->where(function ($query) {
                    $company = $this->company;
                    $query->where('id_company', $company)->where('status', 'active');
                })
            ],
            // 'nominal' => 'required|numeric|min:0'
        ];
        $this->validate($request, $rule);
        $voucher = CashBackVoucher::where('id', $request->id)->where('expired_at', '>',
            Carbon::now()->toDateTimeString())->first();

        $order = OrderAds::where('voucher_cashback_id', $voucher->id)->where('status', '!=', '4')->count();
        $promoVoucher = PromoCode::where('id', $request->get('promo_codeid'))->first();
        $total_price = str_replace(',', '', $request->get('total_price'));
        $grandTotal = $request->get('grand_total');
        $gxpValue = $request->get('gxp_value');
        $gxp_total = 0;
        $credit_card = null;

        if (!$voucher) {
            return response()->json([
                'message' => '',
                'errors' => [
                    'id' => [
                        'Voucher invalid'
                    ]
                ]
            ], 422);
        }

        if ($promoVoucher) {
            return response()->json([
                'message' => '!',
                'errors' => [
                    'id' => [
                        'The voucher is already in use'
                    ]
                ]
            ], 422);
        }

        if ($order >= 1) {
            return response()->json([
                'message' => '',
                'errors' => ['id' => ['Kode Voucher sudah digunakan']]
            ], 422);
        }

        if ($grandTotal || $grandTotal === '0') {
            if ((double)$voucher->nominal * 2 > (double)$grandTotal) {
                return response()->json([
                    'message' => 'Minimal nominal transaksi ' . format_priceID($voucher->nominal * 2),
                ], 403);
            }
        } else {
            if ((double)$voucher->nominal * 2 > (double)$total_price) {
                return response()->json([
                    'message' => 'Minimal nominal transaksi ' . format_priceID($voucher->nominal * 2),
                ], 403);
            }
        }

        if ($gxpValue) {
            $gxp = $this->mygxp();

            if ($gxp['gxp'] >= (double)$total_price) {
                $gxp_total = $gxp['gxp'] - (double)$total_price;
                $note = 'sisan gxp point';
            } else {
                $gxp_total = ((double)$total_price - $gxp['gxp']);
                $note = 'kalkulasi gxp';
            }

            if ((double)$gxp_total >= (double)$voucher->nominal) {
                $gxp_total = (double)$gxp_total - (double)$voucher->nominal;
            }
        } else {
            $gxp = 0;
            $credit_card = null;
            $gxp_total = (double)$total_price - (double)$voucher->nominal;
        }

        if ($request->get('payment_method') == 'credit_card') {
            $credit_card = ceil(((100 / 97.1) * (double)$gxp_total) - (double)$gxp_total);
            $gxp_total = $gxp_total + $credit_card;
        }

        return response()->json([
            'message' => 'ok',
            'result' => $voucher->only('id', 'nominal'),
            'nominal' => $voucher->nominal,
            'currency' => $voucher->currency,
            'myvoucher_text' => number_format($voucher->nominal, 0),
            'myvoucher_id' => $voucher->id,
            'gxp_total' => $gxp_total,
            'gxpValue' => $gxpValue,
            'credit_card' => $credit_card,
        ]);
    }

    /**
     * function check promocode
     *
     * @param mixed $request
     *
     * @return void
     */
    public function promocode(Request $request)
    {
        $rule = [
            // 'id' => [
            //     'required',
            //     Rule::exists('tbl_promo_code', 'id')->where('status', 1)
            // ],
            'code' => [
                'required',
                Rule::exists('tbl_promo_code', 'code')->where('status', '1'),
            ],
            // 'amount' => 'required|numeric|min:0'
        ];

        $this->validate($request, $rule, ['code.required' => trans('premium.validation.required_voucher_code')]);
        $company = auth('web')->user()->company;
        $promo = PromoCode::where('code', $request->get('code'))->first();
        // $order_use = OrderAds::where('promo_code_id', $promo->id)->count();

        $cashbackVoucher = CashBackVoucher::where('id', $request->cash_back_id)->where('expired_at', '>',
            Carbon::now()->toDateTimeString())->first();

        $order_use = OrderAds::where('promo_code_id', $promo->id)->whereHas('adsOrder',
            function ($query) use ($company) {
                $query->where('company_id', $company->id_company);
            })->count('promo_code_id');
        $grand_promo = 0;
        $gxp_total = 0;
        $total_price = str_replace(',', '', $request->get('total_price'));
        $grandTotal = $request->get('grand_total');
        $gxpValue = $request->get('gxp_value');

        if ($order_use >= $promo->provider_max_use) {
            return response()->json([
                'message' => '!',
                'errors' => [
                    'code' => [
                        'Max Promo has been reached'
                    ]
                ]
            ], 422);
        }

        if ($order_use >= $promo->general_max_use) {
            return response()->json([
                'message' => '!',
                'errors' => [
                    'code' => [
                        'Max Promo has been reached'
                    ]
                ]
            ], 422);
        }

        if ($cashbackVoucher) {
            return response()->json([
                'message' => '!',
                'errors' => [
                    'code' => [
                        'The voucher is already in use'
                    ]
                ]
            ], 403);
        }

        if ($promo->type == 'percentage') {
            $promoAmount = (double)($promo->amount / 100) * (double)$total_price;
        } else {
            $promoAmount = (double)$promo->amount;
        }

        if ($promo->max_amount) {
            if ($promoAmount >= $promo->max_amount) {
                $promoAmount = $promo->max_amount;
            }
        }


        if ($promo->is_always_available === 1 && $promo->start_date === null && $promo->end_date === null) {
            $promoAmount = $promoAmount;

        } else {
            if ($promo->start_date > Carbon::now()->toDateTimeString()) {
                return response()->json([
                    'message' => '!',
                    'errors' => [
                        'code' => [
                            'Promo is not active'
                        ]
                    ]
                ], 422);
            }

            if ($promo->end_date < Carbon::now()->toDateTimeString()) {
                return response()->json([
                    'message' => '!',
                    'errors' => [
                        'code' => [
                            'Promo has been expired'
                        ]
                    ]
                ], 422);
            }

        }

        if ($grandTotal || $grandTotal === '0') {
            if ((double)$grandTotal < (double)$promo->minimum_transaction) {
                return response()->json([
                    'message' => '!',
                    'errors' => [
                        'code' => [
                            'Minimum payment is IDR ' . number_format($promo->minimum_transaction, 0)
                        ]
                    ]
                ], 403);
            }

            if ((double)$grandTotal < (double)$promoAmount) {
                return response()->json([
                    'message' => '!',
                    'errors' => [
                        'code' => [
                            'Minimum payment is IDR ' . number_format($promoAmount, 0)
                        ]
                    ]
                ], 403);
            }
        } else {
            if ((double)$total_price < (double)$promo->minimum_transaction) {
                return response()->json([
                    'message' => '!',
                    'errors' => [
                        'code' => [
                            'Minimum payment is IDR ' . number_format($promo->minimum_transaction, 0)
                        ]
                    ]
                ], 403);
            }

            if ((double)$total_price < (double)$promoAmount) {
                return response()->json([
                    'message' => '!',
                    'errors' => [
                        'code' => [
                            'Minimum payment is IDR ' . number_format($promoAmount, 0)
                        ]
                    ]
                ], 403);
            }
        }

        $credit_card = null;
        if ($gxpValue) {
            $gxp = $this->mygxp();

            if ($gxp['gxp'] >= (double)$total_price) {
                $gxp_total = $gxp['gxp'] - (double)$total_price;
                $note = 'sisa gxp point';
            } else {
                $gxp_total = ((double)$total_price - $gxp['gxp']);
                $note = 'kalkulasi gxp';
            }

            if ((double)$gxp_total >= (double)$promoAmount) {
                $gxp_total = (double)$gxp_total - (double)$promoAmount;
            }
        } else {
            $gxp = 0;
            if ((double)$total_price >= (double)$promoAmount) {
                $gxp_total = (double)$total_price - (double)$promoAmount;
            }
        }

        if ($request->get('payment_method') == 'credit_card') {
            $credit_card = ceil(((100 / 97.1) * (double)$gxp_total) - (double)$gxp_total);
            $gxp_total = $gxp_total + $credit_card;
        }

        return response()->json([
            'message' => 'Ready',
            'promo_amount' => $promoAmount,
            'promo_text' => number_format($promoAmount, 0),
            'promo_codeid' => $promo->id,
            'grandTotal' => $grandTotal,
            'gxpValue' => $gxpValue,
            'gxp_total' => $gxp_total,
            'credit_card' => $credit_card,
        ]);

    }

    /**
     * function check gxp
     *
     * @param mixed $request
     *
     * @return void
     */
    public function checkgxp(Request $request)
    {
        // $rule = [
        //     'id' => [
        //         'required',
        //         Rule::exists('tbl_journal_gxp', 'id')->where('type', 'in'),
        //     ],
        // ];

        // $this->validate($request,$rule);
        $gxp = $this->mygxp();
        $grand_promo = 0;
        $grand_cashback = 0;
        $gxp_total = 0;
        $total_price = str_replace(',', '', $request->get('total_price'));
        $grandTotal = $request->get('grand_total');
        $promo_amount = $request->get('promo_amount');
        $cashback_amount = $request->get('cashback_amount');
        $gxpValue = $request->get('gxp_value');
        $credit_card = 0;
        $cc = false;
        // Jika Gxp yang di jalankan saja
        if ($gxp['gxp'] >= (double)$total_price || $gxp['gxp'] >= (double)$grandTotal) {
//
//            if ($request->get('payment_method') == 'credit_card') {
//                $credit_card = ceil(((100 / 97.1) * (double)$total_price) - (double)$total_price);
//            }
            $grandTotal = $total_price;

            $gxp_total = ($gxp['gxp'] - (double)$total_price) - (double)$credit_card;
            $note = 'sisa gxp point 0';

            if ($promo_amount) {
                $gxp_total = $gxp['gxp'] - (double)$grandTotal;
            }

            if ($gxp['gxp'] <= 0) {
                return response()->json([
                    'message' => trans('premium.validation.gxp_cant_used'),
                ], 403);
            }

            if ((double)$grandTotal <= 0) {
                return response()->json([
                    'message' => trans('premium.validation.gxp_cant_used'),
                ], 403);
            }


            // return response()->json([
            //     'message' => '!',
            //     'errors' => 'Salah bos'
            // ]);
        } else {
            if ($gxp['gxp'] <= 0) {
                return response()->json([
                    'message' => trans('premium.validation.gxp_cant_used'),
                ], 403);
            }
            $gxp_total = ((double)$total_price - $gxp['gxp']);
            $note = 'kalkulasi gxp';

            // Jika promo tersedia
            if ($promo_amount) {
                if ($gxp_total > $promo_amount) {
                    $gxp_total = $gxp_total - $promo_amount;
                }
            }
            // Jika cashback tersedia
            if ($cashback_amount) {
                if ($gxp_total > $cashback_amount) {
                    $gxp_total = $gxp_total - $cashback_amount;
                }
            }

            if ($request->get('payment_method') == 'credit_card') {
                $credit_card = ceil(((100 / 97.1) * (double)$gxp_total) - (double)$gxp_total);
                $gxp_total = $gxp_total + $credit_card;
                $cc = true;
            }
        }

        if (!$request->get('payment_method')) {
            return response()->json([
                'message' => 'Payment method required'
            ], 403);
        }


        return response()->json([
            'message' => 'Ok',
            'gxp_promo' => $grand_promo,
            'gxp_cashback' => $grand_cashback,
            'gxp_total' => $gxp_total,
            'promo_amount' => $request->get('promo_amount'),
            'gxp_amount' => $gxp['gxp'],
            'grandTotal' => $grandTotal,
            'credit_card' => $credit_card,
            'show_cc' => $cc
        ]);

    }

    /**
     * function check creditCard
     *
     * @param mixed $request
     *
     * @return void
     */
    public function creditCard(Request $request)
    {
        $select = $request->get('select');
        $gxp_total = 0;
        $total_price = str_replace(',', '', $request->get('total_price'));
        $grandTotal = $request->get('grand_total');
        $gxpValue = $request->get('gxp_value');
        $credit_card = null;
        $cashbackVoucher = CashBackVoucher::where('id', $request->get('cash_back_id'))->where('expired_at', '>',
            Carbon::now()->toDateTimeString())->first();
        $promo = PromoCode::where('code', $request->get('code'))->first();
        $promoAmount = 0;
        $remaining = 0;
        if ($select == 'credit_card') {

            if ($gxpValue) {
                $gxp = $this->mygxp();
                if ($gxp['gxp'] >= (double)$total_price) {

                    $remaining = $gxp['gxp'] - (double)$total_price;
                    $note = 'sisa gxp point 0';

                    // New validate
//                    if ($promo || $cashbackVoucher) {
//                        if ($grandTotal === '0') {
//                            return response()->json([
//                                'message' => 'Sorry can not use'
//                            ], 403);
//                        }
//                    }

                    if ($promo) {
                        if ($promo->type == 'percentage') {
                            $promoAmount = (double)($promo->amount / 100) * (double)$total_price;
                            $res = 'type percentage';
                        } else {
                            $promoAmount = (double)$promo->amount;
                        }

                        if ($promo->max_amount) {
                            if ($promoAmount >= $promo->max_amount) {
                                $promoAmount = $promo->max_amount;
                            }
                        }

                        $gxp_total = $gxp['gxp'] - ((double)$total_price - (double)$promoAmount);
                    }
                    if ($cashbackVoucher) {
                        if ((double)$gxp_total > (double)$cashbackVoucher->nominal) {
                            $gxp_total = (double)$gxp_total - (double)$cashbackVoucher->nominal;
                        }
                    }

//                    $tes = ((double)$total_price + $credit_card) -  (double)$request->get('gxp_amount');
                    $credit_card = ceil(((100 / 97.1) * (double)$gxp_total) - (double)$gxp_total);
                    $gxp_total = $total_price + $credit_card;

                } else {
                    $gxp_total = ((double)$total_price - $gxp['gxp']);
                    $note = 'kalkulasi gxp';

                    // Jika promo tersedia
                    if ($promo) {
                        if ($promo->type == 'percentage') {
                            $promoAmount = (double)($promo->amount / 100) * (double)$total_price;
                            $res = 'type percentage';
                        } else {
                            $promoAmount = (double)$promo->amount;
                        }

                        if ($promo->max_amount) {
                            if ($promoAmount >= $promo->max_amount) {
                                $promoAmount = $promo->max_amount;
                            }
                        }

                        if ((double)$gxp_total > (double)$promoAmount) {
                            $gxp_total = (double)$gxp_total - (double)$promoAmount;
                        }
                    }
                    // Jika cashback tersedia
                    if ($cashbackVoucher) {
                        if ((double)$gxp_total > (double)$cashbackVoucher->nominal) {
                            $gxp_total = (double)$gxp_total - (double)$cashbackVoucher->nominal;
                        }
                    }
                    $credit_card = ceil(((100 / 97.1) * (double)$gxp_total) - (double)$gxp_total);
                    $gxp_total = $gxp_total + $credit_card;

                }

            } else {
                $gxp_total = (double)$total_price;

                $credit_card = ceil(((100 / 97.1) * (double)$gxp_total) - (double)$gxp_total);
                $gxp_total = $gxp_total + $credit_card;
            }


            if ($promo) {
                if ($promo->type == 'percentage') {
                    $promoAmount = (double)($promo->amount / 100) * (double)$total_price;
                    $res = 'type percentage';
                } else {
                    $promoAmount = (double)$promo->amount;
                }

                if ($promo->max_amount) {
                    if ($promoAmount >= $promo->max_amount) {
                        $promoAmount = $promo->max_amount;
                    }
                }

                if (!$gxpValue) {

                    $gxp = 0;
                    if ((double)$total_price >= (double)$promoAmount) {
                        $gxp_total = (double)$total_price - (double)$promoAmount;
                    } else {
                        return response()->json([
                            'message' => 'Total should not be minus',
                        ], 403);
                    }
                } else {
                    $gxp = $this->mygxp();

                    if ($gxp['gxp'] >= (double)$total_price) {
                        $remaining = $gxp['gxp'] - (double)$total_price;
                        $note = 'sisan gxp point';

                        $gxp_total = $total_price;
                    } else {
                        $gxp_total = ((double)$total_price - $gxp['gxp']);
                        $note = 'kalkulasi gxp';
                    }

                    if ((double)$gxp_total >= (double)$promoAmount) {
                        $gxp_total = (double)$gxp_total - (double)$promoAmount;
                    }
                }

                $credit_card = ceil(((100 / 97.1) * (double)$gxp_total) - (double)$gxp_total);
                $gxp_total = $gxp_total + $credit_card;
            }

            if ($cashbackVoucher) {
                if ($gxpValue) {
                    $gxp = $this->mygxp();

                    if ($gxp['gxp'] >= (double)$total_price) {
                        $remaining = $gxp['gxp'] - (double)$total_price;
                        $note = 'sisa gxp point';

                        $gxp_total = $total_price;
                    } else {
                        $gxp_total = ((double)$total_price - $gxp['gxp']);
                        $note = 'kalkulasi gxp';
                    }

                    if ((double)$gxp_total >= (double)$cashbackVoucher->nominal) {
                        $gxp_total = (double)$gxp_total - (double)$cashbackVoucher->nominal;
                    }
                } else {
                    $gxp = 0;
                    $gxp_total = (double)$total_price - (double)$cashbackVoucher->nominal;
                }

                $credit_card = ceil(((100 / 97.1) * (double)$gxp_total) - (double)$gxp_total);
                $gxp_total = $gxp_total + $credit_card;
            }


        } else {

            if ($gxpValue) {
                $gxp = $this->mygxp();
                if ($gxp['gxp'] >= (double)$total_price) {

                    $remaining = $gxp['gxp'] - (double)$total_price;
                    $note = 'sisa gxp point 0';
                    $gxp_total = $total_price;

                    if ($promo) {
                        if ($promo->type == 'percentage') {
                            $promoAmount = (double)($promo->amount / 100) * (double)$total_price;
                            $res = 'type percentage';
                        } else {
                            $promoAmount = (double)$promo->amount;
                        }

                        if ($promo->max_amount) {
                            if ($promoAmount >= $promo->max_amount) {
                                $promoAmount = $promo->max_amount;
                            }
                        }
                        $gxp_total = $gxp['gxp'] - ((double)$total_price - (double)$promoAmount);
                    }

                    // Jika cashback tersedia
                    if ($cashbackVoucher) {
                        if ((double)$gxp_total > (double)$cashbackVoucher->nominal) {
                            $gxp_total = (double)$gxp_total - (double)$cashbackVoucher->nominal;
                        }
                    }


                } else {
                    $gxp_total = ((double)$total_price - $gxp['gxp']);
                    $note = 'kalkulasi gxp';

                    // Jika promo tersedia
                    if ($promo) {
                        if ($promo->type == 'percentage') {
                            $promoAmount = (double)($promo->amount / 100) * (double)$total_price;
                            $res = 'type percentage';
                        } else {
                            $promoAmount = (double)$promo->amount;
                        }

                        if ($promo->max_amount) {
                            if ($promoAmount >= $promo->max_amount) {
                                $promoAmount = $promo->max_amount;
                            }
                        }

                        if ((double)$gxp_total > (double)$promoAmount) {
                            $gxp_total = (double)$gxp_total - (double)$promoAmount;
                        }
                    }
                    // Jika cashback tersedia
                    if ($cashbackVoucher) {
                        if ((double)$gxp_total > (double)$cashbackVoucher->nominal) {
                            $gxp_total = (double)$gxp_total - (double)$cashbackVoucher->nominal;
                        }
                    }
                }
            } else {
                $gxp_total = $total_price;
            }

            if ($promo) {
                if ($promo->type == 'percentage') {
                    $promoAmount = (double)($promo->amount / 100) * (double)$total_price;
                    $res = 'type percentage';
                } else {
                    $promoAmount = (double)$promo->amount;
                }

                if ($promo->max_amount) {
                    if ($promoAmount >= $promo->max_amount) {
                        $promoAmount = $promo->max_amount;
                    }
                }

                if (!$gxpValue) {

                    $gxp = 0;
                    if ((double)$total_price >= (double)$promoAmount) {
                        $gxp_total = (double)$total_price - (double)$promoAmount;
                    } else {
                        return response()->json([
                            'message' => 'Total should not be minus',
                        ], 403);
                    }
                } else {
                    $gxp = $this->mygxp();

                    if ($gxp['gxp'] >= (double)$total_price) {
                        $remaining = $gxp['gxp'] - (double)$total_price;
                        $note = 'sisan gxp point';

                        $gxp_total = $total_price;
                    } else {
                        $gxp_total = ((double)$total_price - $gxp['gxp']);
                        $note = 'kalkulasi gxp';
                    }

                    if ((double)$gxp_total >= (double)$promoAmount) {
                        $gxp_total = (double)$gxp_total - (double)$promoAmount;
                    }
                }
            }


            if ($cashbackVoucher) {
                if ($gxpValue) {
                    $gxp = $this->mygxp();

                    if ($gxp['gxp'] >= (double)$total_price) {
                        $remaining = $gxp['gxp'] - (double)$total_price;
                        $note = 'sisa gxp point';

                        $gxp_total = $total_price;
                    } else {
                        $gxp_total = ((double)$total_price - $gxp['gxp']);
                        $note = 'kalkulasi gxp';
                    }

                    if ((double)$gxp_total >= (double)$cashbackVoucher->nominal) {
                        $gxp_total = (double)$gxp_total - (double)$cashbackVoucher->nominal;
                    }
                } else {
                    $gxp = 0;
                    $gxp_total = (double)$total_price - (double)$cashbackVoucher->nominal;
                }
            }
        }


        return response()->json([
            'gxp_total' => $gxp_total,
            'credit_card' => $credit_card,
        ]);
    }


    public function faq()
    {
        // return view('dashboard.company.ads.faq');
    }

    /**
     * function load datatables gxp
     *
     * @return void
     */
    private function loadDataGxp()
    {
        $company = auth('web')->user()->company;
        $ads = JournalGXP::where('id_company', $company->id_company)->whereIn('type',
            ['out', 'in'])->orderBy('created_at', 'desc')->get();
        return DataTables::of($ads)
            ->editColumn('created_at', function ($data) {
                return $data->created_at->format('d/m/Y');
            })
            ->addColumn('action', function ($data) {
                if ($data->type == 'in') {
                    $html = '<span style="color: green">' . $data->description . '</span>';
                } else {
                    $html = '<span>' . $data->description . '</span>';
                }
                return $html;
            })
            ->make(true);
    }

    /**
     * function show journal gxp
     *
     * @param mixed $request
     *
     * @return void
     */
    public function gxp(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            return $this->loadDataGxp($request);
        }
        $company = auth('web')->user()->company;
        $gxp_history = JournalGXP::whereIdCompany($company->id_company)->whereIn('type',
            ['out', 'in'])->orderBy('created_at', 'desc')->paginate(10);
        // $gxp_sum = JournalGXP::where('type', 'in')->sum('nominal');
        $gxp = $this->mygxp();
        if (auth()->user()->company->is_klhk == 1) {
            return view('klhk.dashboard.company.ads.gxp', compact('gxp', 'gxp_history'));
        }
        return view('dashboard.company.ads.gxp', compact('gxp', 'gxp_history'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * testmail store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function testmail(Request $request)
    {
        $this->initalize($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param mixed $request
     *
     * @return void
     */
    public function store(AdsRequest $request)
    {
        $this->initalize($request);
        try {
            \DB::beginTransaction();

            $request->merge([
                'category_ads' => implode(' & ', $request->input('category_ads'))
            ]);

            $subject = 'Invoice Order ' . $request->category_ads;
            $to = "store@mygomodo.com";
            $res = '';
            $redeem = false;
            $gxp_total = 0;
            $credit_card = null;
            $remaining = 0;
            $total_price = str_replace(',', '', $request->get('total_price'));
            $grandTotal = $request->get('grand_total');
            $gxpValue = $request->get('gxp_value');
            $payment = PaymentMethod::where('id', $request->payment_method)->first();
            $company = \App\Models\Company::find($this->company);
            $user = \App\Models\UserAgent::find($this->user);

            $cashbackVoucher = CashBackVoucher::where('id', $request->cash_back_id)->where('expired_at', '>',
                Carbon::now()->toDateTimeString())->first();
            $promo = PromoCode::where('code', $request->get('code'))->first();
            $promoAmount = 0;
            $id_promo = null;
            if ($promo) {
                $id_promo = $promo->id;
            }

            $id_cashback = null;
            if ($cashbackVoucher) {
                $id_cashback = $cashbackVoucher->id;
            }

            $cashback_amount = null;
            if ($cashbackVoucher) {
                $cashback_amount = $cashbackVoucher->nominal;
            }

            $noinvoice = 'PREM' . date('ymd') . rand(100000, 999999);
            while ($check = OrderAds::where('no_invoice', $noinvoice)->first()) {
                $noinvoice = 'PREM' . date('ymd') . rand(100000, 999999);
            }
            // if($request->has('code') && $request->get('code') !== null && $request->get('code') !== ''){
            //     $order = OrderAds::where('voucher_cashback_id', $cashbackVoucher->id)->where('status', '!=','4')->count();
            //     $order_use = OrderAds::where('promo_code_id', $promo->id)->count();
            //     $order_provider = OrderAds::whereHas('adsOrder', function($query) use ($company){
            //         $query->where('company_id', $company->id_company);
            //     })->count('promo_code_id');

            //     if($order_provider >= $promo->provider_max_use){
            //         $res = 'max use';
            //         $promo = null;
            //     }elseif($order_use >= $promo->general_max_use){
            //         $res = 'max use';
            //         $promo = null;
            //     }elseif($total_price < $promo->amount){
            //         $res = 'min not reached';
            //         $promo = null;
            //     }elseif($cashbackVoucher){
            //         $res = 'promo is already';
            //         $promo = null;
            //     }
            // }


//            if ($gxpValue === '1') {
//                $gxp = $this->mygxp();
//                if ($gxp['gxp'] >= (double)$total_price) {
//
//                    $gxp_total = $gxp['gxp'] - (double)$total_price;
//                    $note = 'sisa gxp point 0';
//
//                    if ($promo) {
//                        if ($promo->type == 'percentage') {
//                            $promoAmount = (double)($promo->amount / 100) * (double)$total_price;
//                            $res = 'type percentage';
//                        } else {
//                            $promoAmount = (double)$promo->amount;
//                        }
//
//                        if ($promo->max_amount) {
//                            if ($promoAmount >= $promo->max_amount) {
//                                $promoAmount = $promo->max_amount;
//                            }
//                        }
//                        $gxp_total = $request->get('gxp_amount') - ((double)$total_price - (double)$promoAmount);
//
//                    }
//
//                    if ((double)$request->get('gxp_amount') <= (double)$total_price) {
//                        $gxp_total = ((double)$total_price - (double)$request->get('gxp_amount')) - (double)$promoAmount;
//                    }
//
//                } else {
//                    $gxp_total = ((double)$total_price - $gxp['gxp']);
//                    $note = 'kalkulasi gxp';
//                    // Jika promo tersedia
//                    if ($promo) {
//                        if ($promo->type == 'percentage') {
//                            $promoAmount = (double)($promo->amount / 100) * (double)$total_price;
//                            $res = 'type percentage';
//                        } else {
//                            $promoAmount = (double)$promo->amount;
//                        }
//
//                        if ($promo->max_amount) {
//                            if ($promoAmount >= $promo->max_amount) {
//                                $promoAmount = $promo->max_amount;
//                            }
//                        }
//
//                        if ((double)$gxp_total > (double)$promoAmount) {
//                            $gxp_total = (double)$gxp_total - (double)$promoAmount;
//                        }
//                    }
//                    // Jika cashback tersedia
//                    if ($cashbackVoucher) {
//                        if ((double)$gxp_total > (double)$cashbackVoucher->nominal) {
//                            $gxp_total = (double)$gxp_total - (double)$cashbackVoucher->nominal;
//                        }
//                    }
//
//                }
//            } else {
//                $gxp_total = (double)$total_price;
//            }


//            if ($promo) {
//                if ($promo->type == 'percentage') {
//                    $promoAmount = (double)($promo->amount / 100) * (double)$total_price;
//                    $res = 'type percentage';
//                } else {
//                    $promoAmount = (double)$promo->amount;
//                }
//
//                if ($promo->max_amount) {
//                    if ($promoAmount >= $promo->max_amount) {
//                        $promoAmount = $promo->max_amount;
//                    }
//                }
//
//                if (!$gxpValue) {
//
//                    $gxp = 0;
//                    if ((double)$total_price >= (double)$promoAmount) {
//                        $gxp_total = (double)$total_price - (double)$promoAmount;
//                    } else {
//                        return response()->json([
//                            'message' => 'Total should not be minus',
//                        ], 403);
//                    }
//                } else {
//                    $gxp = $this->mygxp();
//
//                    if ($gxp['gxp'] >= (double)$total_price) {
//                        $gxp_total = $gxp['gxp'] - (double)$total_price;
//                        $note = 'sisan gxp point';
//                    } else {
//                        $gxp_total = ((double)$total_price - $gxp['gxp']);
//                        $note = 'kalkulasi gxp';
//                    }
//
//                    if ((double)$gxp_total >= (double)$promoAmount) {
//                        $gxp_total = (double)$gxp_total - (double)$promoAmount;
//                    }
//                }
//            }

            // if($request->has('cash_back_id') && $request->get('cash_back_id') !== null && $request->get('cash_back_id') !== ''){
            //     if($order >= 1){
            //         $res = 'Max use voucher';

            //     }elseif(!$cashbackVoucher){
            //         $res = 'Voucher Valid';

            //     }elseif($promo){
            //         $res = 'promo is already';
            //         // $voucher = null;
            //     }
            // }

//            if ($cashbackVoucher) {
//                if ($gxpValue) {
//                    $gxp = $this->mygxp();
//
//                    if ($gxp['gxp'] >= (double)$total_price) {
//                        $gxp_total = $gxp['gxp'] - (double)$total_price;
//                        $note = 'sisa gxp point';
//                    } else {
//                        $gxp_total = ((double)$total_price - $gxp['gxp']);
//                        $note = 'kalkulasi gxp';
//                    }
//
//                    if ((double)$gxp_total >= (double)$cashbackVoucher->nominal) {
//                        $gxp_total = (double)$gxp_total - (double)$cashbackVoucher->nominal;
//                    }
//                } else {
//                    $gxp = 0;
//                    $gxp_total = (double)$total_price - (double)$cashbackVoucher->nominal;
//                }
//            }

//            if ($request->get('payment_method') == 'credit_card') {
//                $credit_card = ceil(((100 / 97.1) * (double)$gxp_total) - (double)$gxp_total);
//                $gxp_total = $gxp_total + $credit_card;
//            }

            // Gxp on
            if ($gxpValue) {
                $gxp = $this->mygxp();
                if ($gxp['gxp'] >= (double)$total_price) {

//                    $remaining = $gxp['gxp'] - (double)$total_price;
                    $note = 'sisa gxp point 0';

                    // New validate
//                    if ($promo || $cashbackVoucher) {
//                        if ($grandTotal === '0') {
//                            return response()->json([
//                                'message' => 'Sorry can not use'
//                            ], 403);
//                        }
//                    }

                    if ($promo) {
                        if ($promo->type == 'percentage') {
                            $promoAmount = (double)($promo->amount / 100) * (double)$total_price;
                            $res = 'type percentage';
                        } else {
                            $promoAmount = (double)$promo->amount;
                        }

                        if ($promo->max_amount) {
                            if ($promoAmount >= $promo->max_amount) {
                                $promoAmount = $promo->max_amount;
                            }
                        }

                        $gxp_total = ((double)$total_price - (double)$promoAmount);
                    }

                    // Jika cashback tersedia
                    if ($cashbackVoucher) {
                        if ((double)$gxp_total > (double)$cashbackVoucher->nominal) {
                            $gxp_total = (double)$gxp_total - (double)$cashbackVoucher->nominal;
                        }
                    }

//                    if ($request->get('payment_method') == 'credit_card') {
//                        $credit_card = ceil(((100 / 97.1) * (double)$gxp_total) - (double)$gxp_total);
//                        $gxp_total = $gxp_total + $credit_card;
//                    }

                    $remaining = $gxp['gxp'] - (double)$gxp_total;
                    $gxp_total = (double)$grandTotal;


                } else {
                    $gxp_total = ((double)$total_price - $gxp['gxp']);
                    $note = 'kalkulasi gxp';

                    // Jika promo tersedia
                    if ($promo) {
                        if ($promo->type == 'percentage') {
                            $promoAmount = (double)($promo->amount / 100) * (double)$total_price;
                            $res = 'type percentage';
                        } else {
                            $promoAmount = (double)$promo->amount;
                        }

                        if ($promo->max_amount) {
                            if ($promoAmount >= $promo->max_amount) {
                                $promoAmount = $promo->max_amount;
                            }
                        }

                        if ((double)$gxp_total > (double)$promoAmount) {
                            $gxp_total = (double)$gxp_total - (double)$promoAmount;
                        }
                    }
                    // Jika cashback tersedia
                    if ($cashbackVoucher) {
                        if ((double)$gxp_total > (double)$cashbackVoucher->nominal) {
                            $gxp_total = (double)$gxp_total - (double)$cashbackVoucher->nominal;
                        }
                    }

                    if ($request->get('payment_method') == 'credit_card') {
                        $credit_card = ceil(((100 / 97.1) * (double)$gxp_total) - (double)$gxp_total);
                        $gxp_total = $gxp_total + $credit_card;
                    }
                }


            } else {
                $gxp_total = (double)$total_price;
                if ($request->get('payment_method') == 'credit_card') {
                    $credit_card = ceil(((100 / 97.1) * (double)$gxp_total) - (double)$gxp_total);
                    $gxp_total = $gxp_total + $credit_card;
                }
            }

            // Promo code
            if ($promo) {
                if ($promo->type == 'percentage') {
                    $promoAmount = (double)($promo->amount / 100) * (double)$total_price;
                    $res = 'type percentage';
                } else {
                    $promoAmount = (double)$promo->amount;
                }

                if ($promo->max_amount) {
                    if ($promoAmount >= $promo->max_amount) {
                        $promoAmount = $promo->max_amount;
                    }
                }

                if (!$gxpValue) {

                    $gxp = 0;
                    if ((double)$total_price >= (double)$promoAmount) {
                        $gxp_total = (double)$total_price - (double)$promoAmount;
                    } else {
                        return response()->json([
                            'message' => 'Total should not be minus',
                        ], 403);
                    }
                } else {
                    $gxp = $this->mygxp();

                    if ($gxp['gxp'] >= (double)$total_price) {
                        $remaining = $gxp['gxp'] - (double)$total_price;
                        $note = 'sisan gxp point';

                        $gxp_total = (double)$grandTotal;
                    } else {
                        $gxp_total = ((double)$total_price - $gxp['gxp']);
                        $note = 'kalkulasi gxp';
                    }

                    if ((double)$gxp_total >= (double)$promoAmount) {
                        $gxp_total = (double)$gxp_total - (double)$promoAmount;
                    }
                }

                if ($request->get('payment_method') == 'credit_card') {
                    $credit_card = ceil(((100 / 97.1) * (double)$gxp_total) - (double)$gxp_total);
                    $gxp_total = $gxp_total + $credit_card;
                }
            }

            // Cashback voucher
            if ($cashbackVoucher) {
                if ($gxpValue) {
                    $gxp = $this->mygxp();

                    if ($gxp['gxp'] >= (double)$total_price) {
                        $remaining = $gxp['gxp'] - (double)$total_price;
                        $note = 'sisa gxp point';

                        $gxp_total = (double)$grandTotal;
                    } else {
                        $gxp_total = ((double)$total_price - $gxp['gxp']);
                        $note = 'kalkulasi gxp';
                    }

                    if ((double)$gxp_total >= (double)$cashbackVoucher->nominal) {
                        $gxp_total = (double)$gxp_total - (double)$cashbackVoucher->nominal;
                    }
                } else {
                    $gxp = 0;
                    $gxp_total = (double)$total_price - (double)$cashbackVoucher->nominal;
                }

                if ($request->get('payment_method') == 'credit_card') {
                    $credit_card = ceil(((100 / 97.1) * (double)$gxp_total) - (double)$gxp_total);
                    $gxp_total = $gxp_total + $credit_card;
                }
            }

            if ($gxp_total <= 0) {
                $redeem = true;
            }

            $gxp_amount = null;
            if ($gxpValue === '1') {
                $gxp = $this->mygxp();
                $gxp_amount = $gxp['gxp'] - $request->get('gxp_balance');
                $journalGxp = JournalGXP::create([
                    'description' => 'Voucher ' . $gxp_amount . ' Applied on premium transaction',
                    'nominal' => $gxp_amount,
                    'currency' => 'IDR',
                    'gxp_type' => 'incentive',
                    'rate' => 1,
                    'id_company' => $company->id_company,
                    'type' => 'out',
                ]);
            }

            $ads = new Ads;
            $ads->company_id = $this->company;
            $ads->purpose = Ads::$purpose[$request->purpose];
            $ads->title = $request->title;
            $ads->url = $request->url ? $request->url : $company->domain_memoria;
            $ads->description = $request->description;
            $ads->gender = Ads::$gender[$request->gender];
            $ads->age = Ads::$age[$request->age];
            $ads->min_budget = str_replace(',', '', $request->min_budget);
            $ads->call_button = Ads::$call_button[$request->call_button];
            $ads->voucher_code = $request->code ? $request->code : null;
            $ads->start_date = date('Y-m-d', strtotime($request->start_date));
            $ads->end_date = date('Y-m-d', strtotime($request->end_date));
            $ads->payment_method_id = 2;
            $ads->term_conditions = $request->term_conditions;
            $ads->service_fee = str_replace(',', '', $request->service_fee);
            $ads->save();
            // $noinvoice = 'PREM'.$this->company.Carbon::now()->format('his').$ads->id.$number;

            if (!$redeem) {
                $dataXendit = [
                    'external_id' => $noinvoice,
                    'amount' => $gxp_total,
                    'payer_email' => $user->email,
                    'description' => 'INVOICE #' . $noinvoice . ' - ' . $request->category_ads ? $request->category_ads : null,
                    'invoice_duration' => Carbon::now()->diffInSeconds(Carbon::now()->addDays(2)),
                ];

                $data_string = json_encode($dataXendit);
                $url = $this->base_url . 'invoices';
                $res = json_decode($this->post_curl($url, $data_string));
                if (isset($res->error_code)) {
                    \DB::rollback();
                    return response()->json(['message' => $res->message], 403);
                }
                $id_res = null;
                if ($res) {
                    $id_res = $res->id;
                }

                $ads->order_ads()->create([
                    'promo_code_id' => $id_promo,
                    'voucher_cashback_id' => $id_cashback,
                    'category_ads' => $request->category_ads ? $request->category_ads : null,
                    'ads_id' => $ads->id,
                    'no_invoice' => $noinvoice,
                    'voucher' => $promoAmount,
                    'gxp_amount' => $gxp_amount ? $gxp_amount : null,
                    'amount' => str_replace(',', '', $request->input('sub_total')),
                    'total_price' => $gxp_total,
                    'fee_credit_card' => $credit_card,
                    'status' => 0,
                    'payment_gateway' => $request->input('payment_method') == 'credit_card' ? 'Xendit Credit Card' : 'Xendit Virtual Account',
                    'reference_number' => $id_res,
                    'status_payment' => $res->status,
                    'amount_payment' => $res->amount,
                    'invoice_url' => $res->invoice_url,
                    'expiry_date' => date('Y-m-d H:i:s', strtotime($res->expiry_date)),
                    'response' => $res,
                ]);
            } elseif ($redeem) {
                $ads->order_ads()->create([
                    'promo_code_id' => $id_promo,
                    'voucher_cashback_id' => $id_cashback,
                    'category_ads' => $request->category_ads ? $request->category_ads : null,
                    'ads_id' => $ads->id,
                    'no_invoice' => $noinvoice,
                    'voucher' => $promoAmount,
                    'gxp_amount' => $gxp_amount ? $gxp_amount : null,
                    'amount' => str_replace(',', '', $request->input('sub_total')),
                    'total_price' => $gxp_total,
                    'fee_credit_card' => $credit_card,
                    'status' => 1,
                    'payment_gateway' => 'Redeem Voucher',
                    'created_at' => date('Y-m-d H:i:s', strtotime(Carbon::now()->toDateTimeString())),
                    'updated_at' => date('Y-m-d H:i:s', strtotime(Carbon::now()->toDateTimeString())),
                ]);

            }


            if ($request->hasFile('document_ads')) {
                $file = $request->file('document_ads');
                $fileName = 'ImageAds' . '-' . $ads->company_id . '-' .
                    '(' . $ads->created_at->format('d-m-Y') . ').'
                    . $file->getClientOriginalExtension();
                $ads->document_ads = $fileName;
                $file->storeAs('public/image-ads', $fileName);

                $dataEmail = [
                    'company_id' => $this->company,
                    'company_name' => $company->company_name,
                    'email_company' => $user->email,
                    'phone_company' => $user->phone,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'no_invoice' => $noinvoice,
                    'purpose' => Ads::$purpose[$request->purpose],
                    'title' => $request->title,
                    'url' => $request->url ? $request->url : $company->domain_memoria,
                    'description' => $request->description,
                    'call_button' => Ads::$call_button[$request->call_button],
                    'gender' => Ads::$gender[$request->gender],
                    'age' => Ads::$age[$request->age],
                    'city' => City::select('city_name')->whereIn('id_city', $request->input('city'))->get()->implode('city_name', ', '),
                    'min_budget' => str_replace(',', '', $request->min_budget),
                    'sub_total' => str_replace(',', '', $request->sub_total),
                    'total_price' => $gxp_total,
                    'gxp_amount' => $gxp_amount ? $gxp_amount : null,
                    'voucher_amount' => $promoAmount ? $promoAmount : null,
                    'cashback_amount' => $cashback_amount ? $cashback_amount : null,
                    'voucher_code' => $request->code ? $request->code : null,
                    'service_fee' => str_replace(',', '', $request->service_fee),
                    'start_date' => date('d M Y', strtotime($request->start_date)),
                    'end_date' => date('d M Y', strtotime($request->end_date)),
                    'payment_method_name' => $request->input('payment_method') == 'credit_card' ? 'Credit Card' : 'Virtual Account',
                    'category_ads' => $request->category_ads ? $request->category_ads : null,
                    'fee_credit_card' => $credit_card,
                    'document_ads' => 'storage/image-ads/' . $fileName,
                    'date_active' => date('d M Y', strtotime($request->start_date)) . ' - ' . date('d M Y',
                            strtotime($request->end_date)),
                    'updated_at' => date('d-m-Y', strtotime(Carbon::now()->toDateTimeString())),
                    'time_updated_at' => date('H:i:s', strtotime(Carbon::now()->toDateTimeString())),
                    'orderAds' => OrderAds::where('ads_id', $ads->id)->first(),
                ];

                if (!$redeem) {
                    // Email to Admin
                    $pdf_admin = 'dashboard.company.ads.mailAdmin';
                    $view_admin = 'dashboard.company.ads.mail_admin_desc';
                    $pdf = 'dashboard.company.ads.mail';
                    $view = 'dashboard.company.ads.mail_provider_desc';

                    $this->mailPremium($pdf_admin, $view_admin, $pdf, $view, $dataEmail, $to);
//                    Mail::send('dashboard.company.ads.mail_admin_desc', $dataEmail, function ($message) use ($pdf, $dataEmail, $to) {
//                        $message->to($to)->subject('Order ' . $dataEmail['category_ads'] . 'from :' . $dataEmail['company_name']);
//                        $message->attachData($pdf->output(), "Data Order Provider -" . $dataEmail['company_name'] . ".pdf");
//                        $message->attach(asset($dataEmail['document_ads']));
//                        $message->from($dataEmail['email_company'], 'Order Premium');
//                    });

                    // Email to Provider
//                    $pdf = PDF::setPaper('A4')->loadView('dashboard.company.ads.mail', $dataEmail);
//                    Mail::send('dashboard.company.ads.mail_provider_desc', $dataEmail, function ($message) use ($pdf, $dataEmail, $to) {
//                        $message->to($dataEmail['email_company'])->subject('Order Invoice ' . $dataEmail['category_ads'] . ' :' . $dataEmail['title']);
//                        $message->attachData($pdf->output(), 'Order ' . $dataEmail['category_ads'] . ' Invoice - ' . Carbon::now()->format('d/m/Y') . '.pdf');
//                        $message->from($to, 'Admin Gomodo');
//                    });


                } elseif ($redeem) {
                    // Email to Admin
                    $pdf_admin = 'dashboard.company.ads.mailAdmin';
                    $view_admin = 'dashboard.company.ads.mail_admin_desc';
                    $pdf = 'dashboard.company.ads.mail.mail_paid';
                    $view = 'dashboard.company.ads.mail.mail_paid';

                    $this->mailPremium($pdf_admin, $view_admin, $pdf, $view, $dataEmail, $to);

//                    $pdf = PDF::setPaper('A4')->loadView('dashboard.company.ads.mailAdmin', $dataEmail);
//                    Mail::send('dashboard.company.ads.mail_admin_desc', $dataEmail, function ($message) use ($pdf, $dataEmail, $to) {
//                        $message->to($to)->subject('Order ' . $dataEmail['category_ads'] . 'from :' . $dataEmail['company_name']);
//                        $message->attachData($pdf->output(), "Data Order Provider -" . $dataEmail['company_name'] . ".pdf");
//                        $message->attach(asset($dataEmail['document_ads']));
//                        $message->from($dataEmail['email_company'], 'Order Premium');
//                    });
//
//                    // Email to Provider paid
//                    $pdf = PDF::setPaper('A4')->loadView('dashboard.company.ads.mail.mail_paid', $dataEmail);
//                    Mail::send('dashboard.company.ads.mail.mail_paid', $dataEmail, function ($message) use ($pdf, $dataEmail, $to) {
//                        $message->to($dataEmail['email_company'])->subject('Order Invoice ' . $dataEmail['category_ads'] . ' :' . $dataEmail['title']);
//                        $message->attachData($pdf->output(), 'Order ' . $dataEmail['category_ads'] . ' Invoice - ' . Carbon::now()->format('d/m/Y') . '.pdf');
//                        $message->from($to, 'Admin Gomodo');
//                    });
                }

            }

            $ads->save();
            $ads->adsCity()->sync($request->city);


            $id = $ads->id;
            $adsBot = Ads::find($id);
            $status = null;

            if ($adsBot->order_ads->status === 0) {
                $status = 'UNPAID';
            } elseif ($adsBot->order_ads->status === 1) {
                $status = 'PAID';
            }

            $content = '** New Order Marketing Solutions **';
            $content .= '```';
            $content .= 'Detail Order Created : ' . Carbon::now()->format('d M Y H:i:s') . '
Company Name : ' . $company->company_name . '
Name : ' . $user->first_name . '
Url : ' . $adsBot->url . '
Product Name : ' . $adsBot->order_ads->category_ads . '
Status : ' . $status . '
Payment Method : ' . $adsBot->order_ads->payment_gateway . '
Sub Total : ' . format_priceID($adsBot->order_ads->amount) . '
Service Fee : ' . format_priceID($adsBot->service_fee) . '
Total : ' . format_priceID($adsBot->order_ads->total_price);
            if ($adsBot->order_ads->promoAds) {
                $content .= '
Voucher Promo Code : ' . $adsBot->order_ads->promoAds->code;
                $content .= '
Voucher Promo Amount : ' . format_priceID($adsBot->order_ads->voucher);
                $content .= '
Voucher Promo By : Gomodo';

            } else {
                $content .= '
Voucher Promo: No';
            }
            if ($adsBot->order_ads->voucherAds) {
                $content .= '
Voucher Cashback Amount : ' . format_priceID($adsBot->order_ads->voucherAds->nominal);
            } else {
                $content .= '
Voucher Cashback: No';
            }
            if ($adsBot->order_ads->gxp_amount) {
                $content .= '
Gxp Amount : ' . format_priceID($adsBot->order_ads->gxp_amount);
            } else {
                $content .= '
Gxp : No';
            }
            $content .= '```';

            $this->sendDiscordNotification($content, 'store');

            \DB::commit();

            return response()->json([
                'status' => 200,
                'message' => \trans('premium.facebook.validate.message'),
                'oops' => \trans('general.whoops'),
                'data' => [
                    'invoice' => $noinvoice,
                    'url' => route('invoice-ads.bank-transfer', ['invoice' => $noinvoice])
                ]
            ]);
        } catch (\Exception $exception) {
            \DB::rollback();
            return apiResponse(500, __('general.whoops'), getException($exception));
        }
    }

    public function storeGoogle(GoogleAdsRequest $request)
    {
        $this->initalize($request);
        \DB::beginTransaction();
        try {
            // Init
            $credit_card = null;
            $remaining = 0;
            $total_price = str_replace(',', '', $request->input('total_price'));
            $grand_total = $request->input('grand_total');
            $gxp_value = $request->input('gxp_value');
            $redeem = false;
            $gxp_total = 0;
            $company = $request->user()->company;

            // PROMO
            $promo = null;
            $promo_amount = 0;
            $promo_id = null;
            if (!empty($request->input('code'))) {
                $promo = PromoCode::where('code', $request->input('code'))->first();

                if (!empty($promo)) {
                    $promo_id = $promo->id;
                }
            }
            // END PROMO

            // CASHBACK
            $cashback = null;
            $cashback_id = null;
            $cashback_amount = null;
            if (!empty($request->input('cash_back_id'))) {
                $cashback = CashBackVoucher::where('id', $request->cash_back_id)->where('expired_at', '>',
                    Carbon::now()->toDateTimeString())->first();

                if (!empty($cashback)) {
                    $cashback_id = $cashback->id;
                    $cashback_amount = $cashback->nominal;
                }
            }
            // END CASHBACK


            // No invoice, Looping jika ditemukan duplicate
            $no_invoice = 'PREM' . now()->format('ymd') . rand(100000, 999999);
            while (OrderAds::where('no_invoice', $no_invoice)->exists()) {
                $no_invoice = 'PREM' . now()->format('ymd') . rand(100000, 999999);
            }
            // END No invoice


            // GXP - JIka menggunakan saldo GXP
            if ($gxp_value) {
                $gxp = $this->mygxp();

                // Jika saldo gxp lebih dari total harga
                if ($gxp['gxp'] >= (double)$total_price) {

                    // Jika promo ada
                    if ($promo) {
                        if ($promo->type == 'percentage') {
                            $promo_amount = (double)($promo->amount / 100) * (double)$total_price;
                            $res = 'type percentage';
                        } else {
                            $promo_amount = (double)$promo->amount;
                        }

                        if ($promo->max_amount) {
                            if ($promo_amount >= $promo->max_amount) {
                                $promo_amount = $promo->max_amount;
                            }
                        }

                        $gxp_total = ((double)$total_price - (double)$promo_amount);
                    }

                    // Jika cashback tersedia
                    if ($cashback) {
                        if ((double)$gxp_total > (double )$cashback->nominal) {
                            $gxp_total = (double)$gxp_total - (double)$cashback->nominal;
                        }
                    }

                    if ($request->input('payment_method') == 'credit_card') {
                        $credit_card = ceil(((100 / 97.1) * (double)$gxp_total) - (double)$gxp_total);
                        $gxp_total = $gxp_total + $credit_card;
                    }

                    $remaining = $gxp['gxp'] - (double)$gxp_total;
                    $gxp_total = (double)$grand_total;
                } else { // JIka Saldo GXP kurang dari total harga, maka kurangi
                    $gxp_total = ((double)$total_price - $gxp['gxp']);

                    // Jika promo tersedia
                    if ($promo) {
                        if ($promo->type == 'percentage') {
                            $promoAmount = (double)($promo->amount / 100) * (double)$total_price;
                            $res = 'type percentage';
                        } else {
                            $promoAmount = (double)$promo->amount;
                        }

                        if ($promo->max_amount) {
                            if ($promo_amount >= $promo->max_amount) {
                                $promo_amount = $promo->max_amount;
                            }
                        }

                        if ((double)$gxp_total > (double)$promoAmount) {
                            $gxp_total = (double)$gxp_total - (double)$promoAmount;
                        }
                    }
                    // Jika cashback tersedia
                    if ($cashback) {
                        if ((double)$gxp_total > (double)$cashback->nominal) {
                            $gxp_total = (double)$gxp_total - (double)$cashback->nominal;
                        }
                    }

                    if ($request->input('payment_method') == 'credit_card') {
                        $credit_card = ceil(((100 / 97.1) * (double)$gxp_total) - (double)$gxp_total);
                        $gxp_total = $gxp_total + $credit_card;
                    }
                }
            } else { // Jika tidak menggunakan GXP
                $gxp_total = (double)$total_price;
                if ($request->input('payment_method') == 'credit_card') {
                    $credit_card = ceil(((100 / 97.1) * (double)$gxp_total) - (double)$gxp_total);
                    $gxp_total = $gxp_total + $credit_card;
                }
            }
            // END GXP


            // PROMO
            if ($promo) {
                if ($promo->type == 'percentage') {
                    $promo_amount = (double)($promo->amount / 100) * (double)$total_price;
                    $res = 'type percentage';
                } else {
                    $promo_amount = (double)$promo->amount;
                }

                if ($promo->max_amount) {
                    if ($promo_amount >= $promo->max_amount) {
                        $promo_amount = $promo->max_amount;
                    }
                }

                if (!$gxp_value) {
                    $gxp = 0;
                    if ((double)$total_price >= (double)$promo_amount) {
                        $gxp_total = (double)$total_price - (double)$promo_amount;
                    } else {
                        return response()->json([
                            'message' => 'Total should not be minus',
                        ], 403);
                    }
                } else {
                    $gxp = $this->mygxp();

                    if ($gxp['gxp'] >= (double)$total_price) {
                        $remaining = $gxp['gxp'] - (double)$total_price;
                        $note = 'sisan gxp point';

                        $gxp_total = (double)$grand_total;
                    } else {
                        $gxp_total = ((double)$total_price - $gxp['gxp']);
                        $note = 'kalkulasi gxp';
                    }

                    if ((double)$gxp_total >= (double)$promoAmount) {
                        $gxp_total = (double)$gxp_total - (double)$promoAmount;
                    }
                }

                if ($request->input('payment_method') == 'credit_card') {
                    $credit_card = ceil(((100 / 97.1) * (double)$gxp_total) - (double)$gxp_total);
                    $gxp_total = $gxp_total + $credit_card;
                }
            }
            // END PROMO

            // Cashback
            if ($cashback) {
                if ($gxp_value) {
                    $gxp = $this->mygxp();

                    if ($gxp['gxp'] >= (double)$total_price) {
                        $remaining = $gxp['gxp'] - (double)$total_price;
                        $note = 'sisa gxp point';

                        $gxp_total = (double)$grand_total;
                    } else {
                        $gxp_total = ((double)$total_price - $gxp['gxp']);
                        $note = 'kalkulasi gxp';
                    }

                    if ((double)$gxp_total >= (double)$cashback->nominal) {
                        $gxp_total = (double)$gxp_total - (double)$cashback->nominal;
                    }
                } else {
                    $gxp = 0;
                    $gxp_total = (double)$total_price - (double)$cashback->nominal;
                }

                if ($request->input('payment_method') == 'credit_card') {
                    $credit_card = ceil(((100 / 97.1) * (double)$gxp_total) - (double)$gxp_total);
                    $gxp_total = $gxp_total + $credit_card;
                }
            }
            // END Cashback

            if ($gxp_total <= 0) {
                $redeem = true;
            }

            $gxp_amount = null;
            if ($gxp_value === '1') {
                $gxp = $this->mygxp();
                $gxp_amount = $gxp['gxp'] - $request->input('gxp_balance');
                $journalGxp = JournalGXP::create([
                    'description' => 'Voucher ' . $gxp_amount . ' Applied on premium transaction',
                    'nominal' => $gxp_amount,
                    'currency' => 'IDR',
                    'gxp_type' => 'incentive',
                    'rate' => 1,
                    'id_company' => $request->user()->id_company,
                    'type' => 'out',
                ]);
            }

            $ads = Ads::create([
                'company_id' => $company->id_company,
                'purpose' => 'Google Ads',
                'title' => $request->input('title1'),
                'title2' => $request->input('title2'),
                'url' => $request->input('url'),
                'description' => $request->input('description'),
                'gender' => 'Semua Gender',
                'age' => 'Semua Umur',
                'min_budget' => $request->input('min_budget'),
                'call_button' => 'Iklan',
                'voucher_code' => $request->input('code', null),
                'start_date' => Carbon::parse($request->input('start_date'))->toDateString(),
                'end_date' => Carbon::parse($request->input('end_date'))->toDateString(),
                'payment_method_id' => 2,
                'term_conditions' => 1,
                'service_fee' => str_replace(',', '', $request->input('service_fee')),
                'phone_number' => $request->input('phone_number'),
                'language' => $request->input('language')
            ]);

            if (!$redeem) {
                $dataXendit = [
                    'external_id' => $no_invoice,
                    'amount' => $gxp_total,
                    'payer_email' => $request->user()->email,
                    'description' => 'INVOICE #' . $no_invoice . ' - Google Ads',
                    'invoice_duration' => Carbon::now()->diffInSeconds(Carbon::now()->addDays(2)),
                ];

                $data_string = json_encode($dataXendit);
                $url = $this->base_url . 'invoices';
                $res = json_decode($this->post_curl($url, $data_string));
                if (isset($res->error_code)) {
                    \DB::rollback();
                    return response()->json(['message' => $res->message], 403);
                }
                $id_res = null;
                if ($res) {
                    $id_res = $res->id;
                }

                $order_ads = $ads->order_ads()->create([
                    'promo_code_id' => $promo_id,
                    'voucher_cashback_id' => $cashback_id,
                    'category_ads' => 'Google Ads',
                    'no_invoice' => $no_invoice,
                    'voucher' => $promo_amount,
                    'gxp_amount' => $gxp_amount ?? null,
                    'amount' => str_replace(',', '', $request->input('sub_total')),
                    'total_price' => $gxp_total,
                    'fee_credit_card' => $credit_card,
                    'status' => 0,
                    'payment_gateway' => $request->input('payment_method') == 'credit_card' ? 'Xendit Credit Card' : 'Xendit Virtual Account',
                    'reference_number' => $id_res,
                    'status_payment' => $res->status,
                    'amount_payment' => $res->amount,
                    'invoice_url' => $res->invoice_url,
                    'expiry_date' => Carbon::parse($res->expiry_date)->format('Y-m-d H:i:s'),
                    'response' => $res,
                ]);
            } elseif ($redeem) {
                $order_ads = $ads->order_ads()->create([
                    'promo_code_id' => $promo_id,
                    'voucher_cashback_id' => $cashback_id,
                    'category_ads' => 'Google Ads',
                    'no_invoice' => $no_invoice,
                    'voucher' => $promo_amount,
                    'gxp_amount' => $gxp_amount ?? null,
                    'amount' => str_replace(',', '', $request->input('sub_total')),
                    'total_price' => $gxp_total,
                    'fee_credit_card' => $credit_card,
                    'status' => 1,
                    'payment_gateway' => 'Redeem Voucher'
                ]);

            }

            // Send Email
            $data_email = [
                'company_id' => $request->user()->id_company,
                'company_name' => $request->user()->company->company_name,
                'email_company' => $request->user()->email,
                'phone_company' => $request->user()->phone,
                'first_name' => $request->user()->first_name,
                'last_name' => $request->user()->last_name,
                'no_invoice' => $no_invoice,
                'purpose' => 'Google Ads',
                'business_category' => BusinessCategory::find($request->business_category[0])->business_category_name,
                'title' => $request->title1,
                'title2' => $request->title2,
                'url' => $request->url ? $request->url : $company->domain_memoria,
                'description' => $request->description,
                'call_button' => null,
                'gender' => 'Semua',
                'age' => 'Semua Umur',
                'city' => City::select('city_name')->whereIn('id_city',
                    $request->input('city'))->get()->implode('city_name', ', '),
                'min_budget' => $request->min_budget,
                'sub_total' => str_replace(',', '', $request->sub_total),
                'total_price' => $gxp_total,
                'gxp_amount' => $gxp_amount ?? null,
                'voucher_amount' => $promo_amount ? $promo_amount : null,
                'cashback_amount' => $cashback_amount ?? null,
                'voucher_code' => $request->code ?? null,
                'service_fee' => str_replace(',', '', $request->service_fee),
                'start_date' => date('d M Y', strtotime($request->start_date)),
                'end_date' => date('d M Y', strtotime($request->end_date)),
                'payment_method_name' => $request->input('payment_method') == 'credit_card' ? 'Credit Card' : 'Virtual Account',
                'category_ads' => 'Google Ads',
                'fee_credit_card' => $credit_card,
                'date_active' => date('d M Y', strtotime($request->start_date)) . ' - ' . date('d M Y',
                        strtotime($request->end_date)),
                'updated_at' => date('d-m-Y', strtotime(Carbon::now()->toDateTimeString())),
                'time_updated_at' => date('H:i:s', strtotime(Carbon::now()->toDateTimeString())),
                'orderAds' => $order_ads,
                'phone_number' => $request->phone_number,
                'language' => collect($request->language)->map(function ($val) {
                    return \App\Models\Ads::$languages[$val][app()->getLocale()];
                })->implode(', ')
            ];
            $pdf_admin = 'dashboard.company.ads.mailAdmin';
            $view_admin = 'dashboard.company.ads.mail_admin_desc';
            $pdf = 'dashboard.company.ads.mail';
            $view = 'dashboard.company.ads.mail_provider_desc';
            $to = "store@mygomodo.com";

            if ($redeem) {
                $pdf = 'dashboard.company.ads.mail.mail_paid';
                $view = 'dashboard.company.ads.mail.mail_paid';
            }

            $this->mailPremium($pdf_admin, $view_admin, $pdf, $view, $data_email, $to);
            // END Send Email

            // Ads city
            $ads->adsCity()->sync($request->input('city'));
            // End Ads city

            // Ads Business Category
            $ads->businessCategory()->sync($request->input('business_category'));
            // End Ads Business category


            $status = $ads->order_ads->status === 0 ? 'UNPAID' : 'PAID';

            $content = '** New Order Marketing Solutions **';
            $content .= '```';
            $content .= 'Detail Order Created : ' . Carbon::now()->format('d M Y H:i:s') . PHP_EOL;
            $content .= 'Company Name : ' . $company->company_name . PHP_EOL;
            $content .= 'Name : ' . $request->user()->first_name . PHP_EOL;
            $content .= 'Url : ' . $ads->url . PHP_EOL;
            $content .= 'Product Name : ' . $ads->order_ads->category_ads . PHP_EOL;
            $content .= 'Status : ' . $status . PHP_EOL;
            $content .= 'Payment Method : ' . $ads->order_ads->payment_gateway . PHP_EOL;
            $content .= 'Sub Total : ' . format_priceID($ads->order_ads->amount) . PHP_EOL;
            $content .= 'Service Fee : ' . format_priceID($ads->service_fee) . PHP_EOL;
            $content .= 'Total : ' . format_priceID($ads->order_ads->total_price) . PHP_EOL;

            if ($ads->order_ads->promoAds) {
                $content .= 'Voucher Promo Code : ' . $ads->order_ads->promoAds->code . PHP_EOL;
                $content .= 'Voucher Promo Amount : ' . format_priceID($ads->order_ads->voucher) . PHP_EOL;
                $content .= 'Voucher Promo By : Gomodo' . PHP_EOL;

            } else {
                $content .= 'Voucher Promo: No' . PHP_EOL;
            }

            if ($ads->order_ads->voucherAds) {
                $content .= 'Voucher Cashback Amount : ' . format_priceID($ads->order_ads->voucherAds->nominal) . PHP_EOL;
            } else {
                $content .= 'Voucher Cashback: No' . PHP_EOL;
            }
            if ($ads->order_ads->gxp_amount) {
                $content .= 'Gxp Amount : ' . format_priceID($ads->order_ads->gxp_amount) . PHP_EOL;
            } else {
                $content .= 'Gxp : No' . PHP_EOL;
            }
            $content .= '```';

            $this->sendDiscordNotification($content, 'store');

            \DB::commit();

            return response()->json([
                'status' => 200,
                'message' => \trans('premium.facebook.validate.message'),
                'oops' => \trans('general.whoops'),
                'data' => [
                    'invoice' => $no_invoice,
                    'url' => route('invoice-ads.bank-transfer', ['invoice' => $no_invoice])
                ]
            ]);


        } catch (\Exception $exception) {
            \DB::rollback();
            return apiResponse(500, __('general.whoops'), getException($exception));
        }
    }

    public function mailPremium($pdf_admin, $view_admin, $pdf, $view, $dataEmail, $to)
    {
        if (env('APP_ENV', 'production') == 'local') {
            $to = "hasutamori@gmail.com";
        }
        if (empty($to)) {
            return true;
        }
        $dataPdf = PDF::setPaper('A4')->loadView($pdf_admin, $dataEmail);
        Mail::send($view_admin, $dataEmail, function ($message) use ($dataPdf, $dataEmail, $to) {
            $message->to($to)->subject('Order ' . $dataEmail['category_ads'] . 'from :' . $dataEmail['company_name']);
            $message->attachData($dataPdf->output(), "Data Order Provider -" . $dataEmail['company_name'] . ".pdf");
            if (isset($dataEmail['document_ads'])) {
                $message->attach(storage_path('app/public/' . str_replace('storage/', '', $dataEmail['document_ads'])));
            }
            $message->from($dataEmail['email_company'], 'Order Premium');
        });

        $data_pdf = PDF::setPaper('A4')->loadView($pdf, $dataEmail);
        Mail::send($view, $dataEmail, function ($message) use ($data_pdf, $dataEmail, $to) {
            $message->to($dataEmail['email_company'])->subject('Order Invoice ' . $dataEmail['category_ads'] . ' :' . $dataEmail['title']);
            $message->attachData($data_pdf->output(),
                'Order ' . $dataEmail['category_ads'] . ' Invoice - ' . Carbon::now()->format('d/m/Y') . '.pdf');
            $message->from($to, 'Admin Gomodo');
        });
    }

    private function post_curl($url, $data_string)
    {

        $headers = array(
            'Content-Type:application/json'
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERPWD, $this->xendit_key . ":");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );

        $result = curl_exec($ch);
        return $result;
    }

    /**
     * function get data xendit
     *
     * @param mixed $invoice
     * @param mixed $request
     *
     * @return void
     */
    public function getDataXendit($invoice, Request $request)
    {
        $company = auth('web')->user()->company;
        $orderAds = \App\Models\OrderAds::where('no_invoice', $invoice)->first();
        $company = \App\Models\Company::find($company->id_company);
        if ($orderAds) {
            if ($company && $orderAds->adsOrder->company_id == $company->id_company) {
                if ($orderAds->status == '1') {
                    return redirect()->route('invoice-ads.success', ['invoice' => $orderAds->no_invoice]);
                }
                if ($orderAds->payment_gateway == 'Xendit Virtual Account') {
                    $res = $orderAds->response;
                    if (auth()->user()->company->is_klhk == 1) {
                        return view('klhk.dashboard.company.ads.virtual_account', compact('company', 'res', 'orderAds'));
                    }
                    return view('dashboard.company.ads.virtual_account', compact('company', 'res', 'orderAds'));
                }
                if ($orderAds->payment_gateway == 'Xendit Credit Card') {
                    return redirect($orderAds->invoice_url . '#credit-card');
                }
                \Session::flash('failed', 'Wrong payment type');
                return redirect()->route('company.dashboard');
            }
            \Session::flash('failed', 'Wrong Company');
            return redirect()->route('company.dashboard');
        }
        \Session::flash('failed', 'No Order Found');
        return redirect()->route('company.dashboard');

    }

    /**
     * function check data order
     *
     * @param mixed $request
     *
     * @return void
     */
    public function checkDataOrder(Request $request)
    {
        $company = auth('web')->user()->company;
        $order = OrderAds::whereHas('adsOrder', function ($query) use ($company) {
            $query->where('company_id', $company->id_company);
        })->where('no_invoice', $request->get('no_invoice'))->first();

        if ($order) {
            if ($order->status == '0') {
                if ($order->payment_gateway == 'Xendit Virtual Account') {
                    $res = $order->response;
                    if (Carbon::parse($res['expiry_date'])->toDateTimeString() < Carbon::now()->toDateTimeString()) {
                        $redirect = route('company.premium.index', 'tab=my-premium');
                        return apiResponse(302, 'status changed', ['redirect' => $redirect]);
                    }
                    return apiResponse(200, 'ok');
                }
            } else {

                if ($order->payment_gateway == 'Xendit Virtual Account') {
                    $redirect = route('invoice-ads.virtual-account', ['invoice' => $order->no_invoice]);
                } else {
                    if ($order->payment_gateway == 'Xendit Credit Card') {
                        return redirect($order->invoice_url . '#credit-card');
                    }
                }
                return apiResponse(302, 'status changed', ['redirect' => $redirect]);
            }
        }

        return apiResponse(404, 'order not found', ['redirect' => \route('company.dashboard')]);
    }

    /**
     * function check success payment
     *
     * @param mixed $request
     *
     * @return void
     */
    public function successPayment(Request $request)
    {
        $company = auth('web')->user()->company;
        $data['orderAds'] = OrderAds::whereHas('adsOrder', function ($query) use ($company) {
            $query->where('company_id', $company->id_company);
        })->where('no_invoice', $request->get('invoice'))->first();
        if ($data['orderAds']->status != 1) {
            return redirect()->route('company.premium.index', 'tab=my-premium');
        }

        $data['company'] = $company;
        $data['companyEmail'] = \App\Models\UserAgent::find($company->id_company);
        if (auth()->user()->company->is_klhk == 1) {
            return view('klhk.dashboard.company.ads.payment.success', $data);
        }
        return view('dashboard.company.ads.payment.success', $data);
    }

    /**
     * function store test submit order
     *
     * @param mixed $request
     *
     * @return void
     */
    public function testsubmit(Request $request)
    {
        $this->initalize($request);
        $faker = \Faker\Factory::create();
        $gender = $faker->randomElement(['male', 'female']);

        $subject = "Invoice Order Facebook Ads";
        $to1 = "hello@mygomodo.com";
        $to2 = "info@gomodo.tech";
        $number = microtime(true);
        $number = str_replace('.', '', $number);

        $company = \App\Models\Company::find($this->company);
        $user = \App\Models\UserAgent::find($this->user);
        $noinvoice = 'INV' . $this->company . Carbon::now()->format('his') . $number;
        dd($noinvoice);
        $data = [
            'company_id' => $this->company,
            'company_name' => $company->company_name,
            'email_company' => $company->email_company,
            'phone_company' => $company->phone_company,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'no_invoice' => $noinvoice,
            'purpose' => $faker->word,
            'title' => $faker->title($gender),
            'url' => $company->domain_memoria,
            'description' => $faker->text,
            'call_button' => $faker->word,
            'gender' => $gender,
            'age' => $faker->numberBetween(25, 40),
            'city' => 'KOTA YOGYAKARTA',
            'min_budget' => $faker->randomNumber(5),
            'total_price' => $faker->randomNumber(7),
            'voucher_code' => $faker->word,
            'start_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
            'end_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
            'payment_method_name' => 'Manual Order',
            'document_ads' => 'storage/image-ads/Image-Ads-52.png',
        ];

        // Email to Admin
        $pdf = PDF::setPaper('A4')->loadView('dashboard.company.ads.mailAdmin', $data);
        Mail::send('dashboard.company.ads.mail_admin_desc', $data, function ($message) use ($pdf, $data, $to2) {
            $message->to($to2)->subject('Order Facebook Ads from :' . $data['company_name']);
            $message->attachData($pdf->output(), "Data Order Provider -" . $data['company_name'] . ".pdf");
            $message->attach(asset($data['document_ads']));
            $message->from($data['email_company'], 'Order Premium');
        });

        // Email to Provider
        $pdf = PDF::setPaper('A4')->loadView('dashboard.company.ads.mail', $data);
        Mail::send('dashboard.company.ads.mail_provider_desc', $data, function ($message) use ($pdf, $data, $to1) {
            $message->to($data['email_company'])->subject('Order Invoice Facebook Ads :' . $data['title']);
            $message->attachData($pdf->output(), "Order Facebook Invoice - " . Carbon::now()->format('d/m/Y') . ".pdf");
            $message->from($to1, 'Admin Gomodo');
        });

        // $ads = new Ads;
        // $ads->company_id = $this->company;
        // $ads->purpose = $faker->word;
        // $ads->title = $faker->title($gender);
        // $ads->url = $company->domain_memoria;
        // $ads->description = $faker->text;
        // $ads->gender = $gender;
        // $ads->age = $faker->numberBetween(25,40);
        // $ads->min_budget = $faker->randomNumber(2);
        // $ads->call_button = $faker->word;
        // $ads->voucher_code = $faker->word;
        // $ads->start_date = $faker->date($format = 'Y-m-d', $max = 'now');
        // $ads->end_date = $faker->date($format = 'Y-m-d', $max = 'now');
        // $ads->payment_method_id = $faker->numberBetween(1,2);
        // $ads->save();
        // $noinvoice = 'INV'.$this->company.Carbon::now()->format('his').$ads->id.$number;
        // $ads->order_ads()->create([
        //     'no_invoice' => $noinvoice,
        //     'amount' => $faker->randomNumber(2),
        //     'total_price' => $faker->randomNumber(2),
        //     'status' => 0,
        //     'response' => 'Waiting',
        //     'ads_id' => $ads->id,
        // ]);

        // if ($request->hasFile('document_ads')) {
        //     $file = $request->file('document_ads');
        //     $fileName = 'ImageAds'.'-'.$ads->company_id.'-'.
        //         '('.$ads->created_at->format('d-m-Y').').'
        //         .$file->getClientOriginalExtension();
        //     $ads->document_ads = $fileName;
        //     $file->storeAs('public/image-ads', $fileName);


        // } 


        // $ads->save();
        // $ads->adsCity()->sync($request->city);

        return json_encode([
            'status' => 200,
            'message' => 'Pesanan anda telah kami terima. Silahkan cek Email anda untuk melihat detail informasi pemesanan anda.',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
