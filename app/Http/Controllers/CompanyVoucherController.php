<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\VoucherFormRequest;
use App\Http\Requests\VoucherFormEditRequest;
use App\Models\Voucher;

class CompanyVoucherController extends Controller
{
    var $company = 0;

    /**
     * __construct
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('host');
        $this->middleware('auth');
        //$this->middleware('company');

    }

    /**
     * Function initalize get data user
     *
     * @param  mixed $request
     *
     * @return void
     */
    private function initalize(Request $request)
    {
        $user = \Auth::user();
        $this->user = $user->id_user_agen;
        $this->company = $user->id_company;
        // $this->company = $request->get('my_company');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function index(Request $request)
    {
        $this->initalize($request);

        if ($request->isXmlHttpRequest()) {
            return $this->loadData($request);
        }

        // $voucher = \App\Models\Voucher::where('id_company', $this->company)->orderBy('created_at', 'desc')->paginate(10);
        $voucher = \App\Models\Voucher::withCount('order_used_total')->withCount('order_paid')->where('id_company', $this->company)->orderBy('created_at', 'desc')->paginate(10);

        if (auth()->user()->company->is_klhk == 1){
            return view('klhk.dashboard.company.voucher.index');
        }
        return view('dashboard.company.voucher.index');
    }

    public function loadData(Request $request)
    {
        $voucher = Voucher::withCount('order_used_total')
            ->withCount('order_paid')
            ->where('id_company', $request->user()->id_company)
            ->orderBy('created_at', 'desc');

        return \DataTables::of($voucher)
            ->editColumn('voucher_code', function ($model) {
                return '<a href="'.route('company.voucher.edit', $model->id_voucher).'">'.$model->voucher_code.'</a>';
            })
            ->editColumn('amount', function ($model) {
                return ($model->voucher_amount_type == 0 ? $model->currency : '').' '.number_format($model->voucher_amount, 0).($model->voucher_amount_type == 1 ? '%' : '');
            })
            ->editColumn('minimum_amount', function ($model) {
                return number_format($model->minimum_amount, 0);
            })
            ->editColumn('order_paid_count', function ($model) {
                return number_format($model->order_paid_count);
            })
            ->rawColumns(['voucher_code'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function create(Request $request)
    {
        $this->initalize($request);
        
        $product_type = \App\Models\ProductType::get();
        $list_currency = \App\Models\Voucher::list_currency();
        $company = $this->company;

        $products = \App\Models\Product::where(['status' => 1, 'id_company' => $this->company])
            ->availableQuota()
            ->orderBy('product_name', 'asc')
            ->get();

        if (auth()->user()->company->is_klhk == 1){
            return view('klhk.dashboard.company.voucher.create', [
                'product_type' => $product_type,
                'list_currency' => $list_currency,
                'company' => $company,
                'products' => $products
            ]);
        }
        return view('dashboard.company.voucher.create', [
            'product_type' => $product_type,
            'list_currency' => $list_currency,
            'company' => $company,
            'products' => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function store(VoucherFormRequest $request)
    {
        $this->initalize($request);

        $start_date = Carbon::createFromFormat('d/m/Y', $request->input('valid_start_date'))->toDateString();
        $end_date = empty($request->input('valid_end_date')) ? Carbon::now()->addYears(2)->toDateString() : Carbon::createFromFormat('d/m/Y', $request->input('valid_end_date'))->toDateString();

        $data = [
            'id_company'            => $this->company,
            'id_customer'           => $request->input('customer', null),
            'voucher_code'          => $request->input('voucher_code'),
            'voucher_type'          => $request->input('voucher_type'),
            'voucher_description'   => $request->input('voucher_description'),
            'minimum_amount'        => $request->input('minimum_amount', 0),
            'currency'              => $request->input('currency'),
            'max_use'               => $request->input('max_use', null),
            'voucher_amount_type'   => $request->input('voucher_amount_type'),
            'voucher_amount'        => $request->input('voucher_amount'),
            // 'valid_start_date'=>date('Y-m-d',strtotime($request->get('valid_start_date'))),
            // 'valid_end_date'=>date('Y-m-d',strtotime($request->get('valid_end_date'))),
            // 'start_date'=>date('Y-m-d',strtotime($request->get('start_date'))),
            // 'end_date'=>date('Y-m-d',strtotime($request->get('end_date'))),
            'valid_start_date'      => $start_date,
            'valid_end_date'        => $end_date,
            'start_date'            => $start_date,
            'end_date'              => $end_date,
            'min_people'            => (int) $request->input('min_people'),
            'max_people'            => $request->input('max_people', null),
            'status'                => $request->input('status', 0),
            'created_by'            => $this->user,
        ];


        if ($request->get('product_category')) {
            $data['id_product_category'] = $request->get('product_category');
        }

        if ($request->get('product_type')) {
            $data['id_product_type'] = $request->get('product_type');
        }

        if ($request->get('product')) {
            $data['id_product'] = $request->get('product');
        }

        $new_instance = \App\Models\Voucher::create($data);

        $new_instance->products()->sync($request->input('products'));

        //return redirect('company/tax');
        return json_encode([
            'status' => 200, 
            'message' => \trans('voucher_provider.process.store_submit'),
            'success' => \trans('voucher_provider.process.success'),
            'oops' => \trans('general.whoops'),
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  mixed $request
     * @param  mixed $voucher
     *
     * @return void
     */
    public function edit(Request $request, \App\Models\Voucher $voucher)
    {
        $this->initalize($request);

        if ($voucher->id_company != $this->company) {
            return response()->json([
                'status' => 400,
                'message' => 'Action not allowed'
            ]);
        }

        $product_type = \App\Models\ProductType::get();
        $product_category = \App\Models\ProductCategory::where(['id_product_type' => $voucher->id_product_type])->get();

        $products = \App\Models\Product::where(['status' => 1, 'id_company' => $this->company])
            ->availableQuota()
            ->orderBy('product_name', 'asc')
            ->get();

        if (auth()->user()->company->is_klhk == 1){
            return view('klhk.dashboard.company.voucher.edit', ['voucher' => $voucher, 'product_type' => $product_type, 'product_category' => $product_category, 'products' => $products]);
        }
        return view('dashboard.company.voucher.edit', ['voucher' => $voucher, 'product_type' => $product_type, 'product_category' => $product_category, 'products' => $products]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  mixed $request
     * @param  mixed $voucher
     *
     * @return void
     */
    public function update(VoucherFormEditRequest $request, \App\Models\Voucher $voucher)
    {
        $this->initalize($request);
        if ($voucher->id_company != $this->company) {
            return response()->json([
                'status' => 400,
                'message' => 'Action not allowed'
            ]);

        }
        if ($voucher->by_gomodo =='1'){
            return response()->json([
                'status' => 400,
                'message' => 'Action not allowed, Voucher by Gomodo'
            ]);
        }

        \DB::transaction(function () use ($voucher, $request) {

            $start_date = Carbon::createFromFormat('d/m/Y', $request->input('valid_start_date'))->toDateString();
            $end_date = empty($request->input('valid_end_date')) ? Carbon::now()->addYears(2)->toDateString() : Carbon::createFromFormat('d/m/Y', $request->input('valid_end_date'))->toDateString();

            $voucher = \App\Models\Voucher::where(['id_company' => $this->company
                , 'id_voucher' => $voucher->id_voucher])
                ->first();
            
            $voucher->update([
                    'id_customer' => $request->get('customer') ? $request->get('customer') : null,
                    'voucher_code' => $request->get('voucher_code'),
                    'voucher_type' => $request->get('voucher_type'),
                    'voucher_description' => $request->get('voucher_description'),
                    'minimum_amount' => $request->get('minimum_amount') ? $request->get('minimum_amount') : 0,
                    'currency' => $request->get('currency'),
                    'id_product_type' => $request->get('product_type') ? $request->get('product_type') : null,
                    'id_product_category' => $request->get('product_category') ? $request->get('product_category') : null,
                    'id_product' => $request->get('product') ? $request->get('product') : null,
                    'voucher_amount_type' => $request->get('voucher_amount_type'),
                    'voucher_amount' => $request->get('voucher_amount'),
//                            'valid_start_date'=>date('Y-m-d',strtotime($request->get('valid_start_date'))),
//                            'valid_end_date'=>date('Y-m-d',strtotime($request->get('valid_end_date'))),
//                            'start_date'=>date('Y-m-d',strtotime($request->get('start_date'))),
//                            'end_date'=>date('Y-m-d',strtotime($request->get('end_date'))),
                    'valid_start_date' => $start_date,
                    'valid_end_date' => $end_date,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'status' => !empty($request->get('status')) ? $request->get('status') : false,
                    'updated_by' => $this->user,
                    'max_use' => $request->get('max_use') ? $request->get('max_use') : null,
                    'min_people'            => (int) $request->input('min_people'),
                    'max_people'            => $request->input('max_people', null),
                ]);
            $voucher->products()->sync($request->input('products'));

        });

        return response()->json([
            'status' => 200,
            'message' => \trans('voucher_provider.process.update_submit'),
            'success' => \trans('voucher_provider.process.success'),
            'oops' => \trans('general.whoops'),
        ]);

    }


}
