<?php

namespace App\Http\Controllers;

use App\Models\Insurance;
use App\Models\Product;
use App\Traits\DiscordTrait;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ProductType;
use App\Models\TagProductValue;
use App\Models\TagProduct;
use App\Models\ProductItinerary;
use App\Models\CustomSchema;
use Illuminate\Support\Facades\File;
use Image;
use App\Models\ProductSchedule;
use App\Exports\CompanyProductExport;
use App\Models\UnitName;

class CompanyProductController extends Controller
{
    use DiscordTrait;
    var $company = 0;
    var $user = 0;

    /**
     * __construct
     *
     * @param  mixed  $request
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('host');
        $this->middleware('auth');
        //$this->middleware('company');
    }

    /**
     * function search product
     *
     * @param  mixed  $request
     *
     * @return void
     */
    public function searchAjax(Request $request)
    {
        $keyword = $request->get('query');
        $where = [];

        if (!empty($keyword)) {
            array_push($where, ['product_name', 'LIKE', '%'.$keyword.'%']);
        }

        $country = Product::where($where)->skip(0)->take(10)->get();
        $d_data = [];
        foreach ($country as $row) {
            array_push($d_data, ['value' => $row->product_name, 'data' => $row->id_product]);
        }
        return response()->json([
            'suggestions' => $d_data

        ]);

    }

    /**
     * Function initalize get data user
     *
     * @param  mixed  $request
     *
     * @return void
     */
    private function initalize()
    {
        $user = Auth::user();
        $this->user = $user->id_user_agen;
        $this->company = $user->id_company;
        // $this->company = $request->get('my_company');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  mixed  $request
     *
     * @return void
     */
    public function index(Request $request)
    {
        $this->initalize($request);
        $product = Product::where('id_company', $this->company)->orderBy('created_at',
            'desc')->paginate(10);
        if (auth()->user()->company->is_klhk == 1){
            return view('klhk.dashboard.company.product.index', ['product' => $product]);
        }
        return view('dashboard.company.product.index', ['product' => $product]);
    }

    /**
     * function load more data
     *
     * @param  mixed  $request
     *
     * @return void
     */
    public function load_more(Request $request)
    {
        $this->initalize($request);
        $offset = !empty($request->segment(4)) ? $request->segment(4) : 0;
        $product = Product::where('id_company', $this->company)->orderBy('created_at',
            'desc')->skip($offset)->take(16)->get();

        $view = view('dashboard.company.product.search', ['product' => $product])->render();
        if (auth()->user()->company->is_klhk == 1){
            $view = view('klhk.dashboard.company.product.search', ['product' => $product])->render();
        }
        return response()->json([
            'status' => 200,
            'message' => 'ok',
            'data' => [
                'view' => $view
                ,
                'offset' => ($offset + $product->count())
            ]
        ]);
    }

    /**
     * function select product type
     *
     * @return void
     */
    public function select()
    {
        $product_type = \App\Models\ProductType::get();
        if (auth()->user()->company->is_klhk == 1){
            return view('klhk.dashboard.company.product.select_type', ['product_type' => $product_type]);
        }
        return view('dashboard.company.product.select_type', ['product_type' => $product_type]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  mixed  $request
     *
     * @return void
     */
    public function create(Request $request)
    {
        $this->initalize($request);
        if (app()->getLocale() == 'id') {
            $name = 'name_ind';
        } else {
            $name = 'name';
        }

        $product_category = TagProduct::where('status', 1)->orderBy($name, 'asc')->get();
        $product_type = ProductType::where('status', 1)->get();
        $mark = \App\Models\Mark::where('id_company', $this->company)->get();
        $tax = \App\Models\Tax::where('id_company', $this->company)->get();
        $duration = Product::list_duration();
        $list_currency = Product::list_currency();
        \JavaScript::put([
            'min_people' => \trans('product_provider.min_people'),
            'min_people_else' => \trans('product_provider.min_people_else'),
            'price_from' => \trans('product_provider.price_from'),
            'price_from_else' => \trans('product_provider.price_from_else'),
        ]);
        $insurances = Insurance::active()->get();
        $units = UnitName::where('is_active', true)->get();

        if (auth()->user()->company->is_klhk == 1){
            return view('klhk.dashboard.company.product.create'
                , [
                    'product_category' => $product_category,
                    'mark' => $mark,
                    'tax' => $tax,
                    'product_type' => $product_type,
                    'duration' => $duration,
                    'list_currency' => $list_currency,
                    'insurances'=>$insurances,
                    'units' => $units
                ]
            );
        }
        return view('dashboard.company.product.create'
            , [
                'product_category' => $product_category,
                'mark' => $mark,
                'tax' => $tax,
                'product_type' => $product_type,
                'duration' => $duration,
                'list_currency' => $list_currency,
                'insurances'=>$insurances,
                'units' => $units
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  mixed  $request
     *
     * @return void
     */
//    public function store(Request $request)
    public function store(\App\Http\Requests\ProductFormRequest $request)
    {
//        dd($request->all());
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $this->initalize($request);

        // $request = app('App\Http\Requests\ProductFormRequest');

        \DB::transaction(function () use ($request, &$id) {
            $product_type = $request->get('product_type');
            $product_category = $request->get('product_category');

            $unique_code = $request->get('unique_code');

            if (empty($unique_code)) {
                if (isset($product_category) AND count($product_category) > 0) {
                    $c = $product_category[0];
                } else {
                    $c = $product_category;
                }
                $unique_code = 'SKU'.$this->company.$product_type.$c.str_replace('.', '', microtime(true));
            }

            if ($request->input('discount_amount_type') == '1') {
                $rule = [
                    'discount_amount' => 'numeric|max:100|min:0'
                ];
                $this->validate($request, $rule);
            }

            $newproduct = Product::create([
                'product_name' => strip_tags($request->get('product_name')),
                'permalink' => $request->get('permalink'),
                'id_product_category' => null,
                'id_product_type' => $product_type,
                'unique_code' => $unique_code,
                'id_city' => $request->get('city'),
                'duration' => $request->get('duration'),
                'duration_type' => $request->get('duration_type'),
                'longitude' => $request->get('long'),
                'latitude' => $request->get('lat'),
                'brief_description' => $request->get('brief_description'),
//                'guide_language'=>$request->get('guide_language'),
                'long_description' => clean($request->get('long_description'), 'simple'),
                'important_notes' => clean($request->get('important_notes'), 'simple'),
                // 'itinerary'=>$request->get('itinerary') ? $request->get('itinerary') : null,
                'min_order' => $request->get('min_order'),
                'max_order' => $request->get('max_order'),
                'min_people' => $request->get('min_people'),
                'max_people' => $request->get('max_people'),
                'created_by' => $this->user,
                'updated_by' => $this->user,
                'currency' => $request->get('currency'),
                'advertised_price' => $request->get('advertised_price'),
                'discount_name' => $request->get('discount_name') ? $request->get('discount_name') : null,
                'discount_amount_type' => strlen($request->get('discount_amount_type')) > 0 ? $request->get('discount_amount_type') : null,
                'discount_amount' => $request->get('discount_amount') ? $request->get('discount_amount') : 0,
                'id_mark' => $request->get('mark') ? $request->get('mark') : null,
                'fee_name' => $request->get('fee_name') ? $request->get('fee_name') : null,
                'fee_amount' => $request->get('fee_amount') ? $request->get('fee_amount') : 0,
                // 'booking_confirmation'=> $request->get('booking_confirmation') ? $request->get('booking_confirmation') : 0,
                'booking_confirmation' => $request->input('booking_confirmation', 1),
                'minimum_notice' => $request->get('minimum_notice'),
                'quota_type' => $request->filled('quota_type')?$request->input('quota_type'):'periode',
                'id_company' => $this->company,
                'status' => $request->get('status') ? $request->get('status') : 0,
                'publish' => $request->get('publish') ? $request->get('publish') : 0,
                'addon1' => $request->get('addon1') ? $request->get('addon1') : 0,
                'addon2' => $request->get('addon2') ? $request->get('addon2') : 0,
                'addon3' => $request->get('addon3') ? $request->get('addon3') : 0,
                'addon4' => $request->get('addon4') ? $request->get('addon4') : 0,
                'addon5' => $request->get('addon5') ? $request->get('addon5') : 0,
                'addon6' => $request->get('addon6') ? $request->get('addon6') : 0,
                'addon7' => $request->get('addon7') ? $request->get('addon7') : 0,
                'addon8' => $request->get('addon8') ? $request->get('addon8') : 0,
                'availability' => $request->get('availability'),
                'show_exclusion' => $request->has('show_exclusion'),
                'vat'   => $request->has('vat')
            ]);
            $newproduct->languages()->sync($request->input('guide_language'));

            ############## INSERT PRICING ###################
            $d_price = [];
            foreach ($request->get('price') as $i => $row) {
                array_push($d_price, [
                    'id_product' => $newproduct->id_product,
                    'currency' => $request->get('currency'),
                    'unit_name_id' => (int) $request->get('unit_name_id')[0],
                    'price_type' => $request->get('price_type')[$i],
                    'price' => $row,
                    'price_from' => $request->get('price_from')[$i],
                    'price_until' => $request->get('price_until')[$i],
                ]);
            }

            \App\Models\ProductPrice::insert($d_price);

            $itinerary = [];
            foreach ($request->get('itinerary') as $i => $row) {
                if (isset($row)) {
                    array_push($itinerary, [
                        'product_id' => $newproduct->id_product,
                        'itinerary' => $row
                    ]);
                }

            }

            ProductItinerary::insert($itinerary);


            $tag = [];
            if ($request->get('product_category') AND count($request->get('product_category')) > 0) {
                foreach ($request->get('product_category') as $i => $row) {
                    array_push($tag, [
                        'product_id' => $newproduct->id_product,
                        'tag_product_id' => $row,
                    ]);
                }

                TagProductValue::insert($tag);
            }

            if ($request->has('insurances') && count($request->get('insurances'))>0){
                foreach ($request->get('insurances') as $idInsurances=>$true){
                    $newproduct->insurances()->attach($idInsurances);
                }
            }

            ################## INSERT TAXES #################
            if ($request->get('tax')) {
                $d_data = [];
                foreach ($request->get('tax') as $row) {
                    array_push($d_data, [
                        'id_product' => $newproduct->id_product,
                        'id_tax' => $row,
                    ]);
                }
                \App\Models\ProductTax::insert($d_data);
            }

            ################ INSERT SCHEDULE #################
            $d_data = [];
            foreach ($request->get('start_date') as $i => $row) {
                array_push($d_data, [
                    'id_product' => $newproduct->id_product,
                    'start_date' => date('Y-m-d', strtotime($row)),
                    'end_date' => $newproduct->availability == '0' ? date('Y-m-d', strtotime($row)) : date('Y-m-d',
                        strtotime($request->get('end_date')[$i])),
                    'start_time' => date('H:i', strtotime($request->get('start_time')[$i])),
                    'end_time' => date('H:i', strtotime($request->get('end_time')[$i])),
                    'sun' => isset($request->get('sun')[$i]) ? $request->get('sun')[$i] : 0,
                    'mon' => isset($request->get('mon')[$i]) ? $request->get('mon')[$i] : 0,
                    'tue' => isset($request->get('tue')[$i]) ? $request->get('tue')[$i] : 0,
                    'wed' => isset($request->get('wed')[$i]) ? $request->get('wed')[$i] : 0,
                    'thu' => isset($request->get('thu')[$i]) ? $request->get('thu')[$i] : 0,
                    'fri' => isset($request->get('fri')[$i]) ? $request->get('fri')[$i] : 0,
                    'sat' => isset($request->get('sat')[$i]) ? $request->get('sat')[$i] : 0,
                    // 'day_type' => isset($request->get('day_type')[$i]) ? $request->get('day_type')[$i] : 0,
                ]);
            }

            \App\Models\ProductSchedule::insert($d_data);

            ################# ISERT IMAGE #####################
            if ($request->hasFile('images')) {
                $d_data = [];
                foreach ($request->file('images') as $i => $file) {
                    // ubah file ext jadi jpg
                    $image_name = pathinfo($file->hashName(), PATHINFO_FILENAME).'.jpg';

                    $sizes = [
                        [
                            'width' => 1600,
                            'height' => 900,
                            'path' => public_path('uploads/products')
                        ],
                        [
                            'width' => null,
                            'height' => 200,
                            'path' => public_path('uploads/products/thumbnail')
                        ]
                    ];

                    foreach ($sizes as $size) {
                        File::isDirectory($size['path']) or File::makeDirectory($size['path'], 0777, true, true);
                        Image::make($file)
                            ->rotate(360 - $request->input('crop-images.'.$i.'.rotate'))
                            ->crop((int)$request->input('crop-images.'.$i.'.width'),
                                (int)$request->input('crop-images.'.$i.'.height'),
                                (int)$request->input('crop-images.'.$i.'.x'),
                                (int)$request->input('crop-images.'.$i.'.y'))
                            ->resize($size['width'], $size['height'], function ($constraint) {
                                return $constraint->aspectRatio();
                            })
                            ->encode('jpeg')
                            ->save($size['path'].'/'.$image_name)
                            ->destroy();
                    }

                    $is_primary = $i == 0 ? true : false;

                    array_push($d_data, [
                        'id_product' => $newproduct->id_product,
                        'url' => $image_name,
                        'is_primary' => $is_primary,
                    ]);
                }

                \App\Models\ProductImage::insert($d_data);
            }

            $id = $newproduct->id_product;

            // Custom Information
            if (!empty(array_filter($request->get('custom_label')))) {
                $custom = collect(array_filter($request->get('custom_label')))->map(function ($value, $key) use (
                    $request
                ) {
                    $values = null;
                    // Jika tipe adalah choices dan checkbox
                    if (in_array($request->input('custom_type.'.$key), ['choices', 'checkbox', 'dropdown'])) {
                        $values = $request->input('custom_values.'.$key);
                    }

                    return [
                        'type_custom' => $request->input('custom_type.'.$key),
                        'label_name' => $value,
                        'fill_type' => $request->input('custom_fill_type.'.$key),
                        'description' => $request->input('custom_description.'.$key),
                        'value' => $values
                    ];
                });

                $newproduct->customSchema()->createMany($custom->toArray());
            }
            // End Custom Information

            // Distribution ota
            //$newproduct->ota()->sync($request->input('ota', []));
            // END Distribution ota

        });


        $product = Product::find($id);
        $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
        $newCompany = $product->company;
        $loc = \Stevebauman\Location\Facades\Location::get($ip);
        $http = env('HTTPS', false) == true ? 'https://' : 'http://';
        $content = '**New Product Created '.Carbon::now()->format('d M Y H:i:s').'**';
        $content .= '```';
        $content .= 'Company Name : '.$newCompany->company_name.'
Domain Gomodo : '.$http.$newCompany->domain_memoria.'
Product Name : '.$product->product_name.'
URL Product : '.$http.$newCompany->domain_memoria.'/product/detail/'.$product->unique_code.'
IP Address : '.$ip.'
City name : '.$loc->cityName.'
Region Name : '.$loc->regionName.'
Country Code : '.$loc->countryCode.'
Pricing:
 ';
        foreach ($product->pricing as $price) {
            $content .= $price->price_from.' - '.$price->price_until.' ('.$price->unit->id.') : '.format_priceID($price->price,
                    $price->currency).'
 ';
        }

        $content .= '```';


        $this->sendDiscordNotification($content, 'product');
        $prefix = $request->isSecure()?'https://':'http://';

        return json_encode([
            'status' => 200,
            'message' => \trans('product_provider.store_submit'),
            'success' => \trans('product_provider.success'),
            'oops' => \trans('general.whoops'),
            'data' => [
                'id' => $id,
                'url' => Route('company.product.edit', $id),
                'preview' =>$prefix.$product->company->domain_memoria.'/product/detail/'.$product->unique_code
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  mixed  $request
     * @param  mixed  $id_product
     *
     * @return void
     */
    public function edit(Request $request, $id_product)
    {
        if (!$product = Product::where('id_product', $id_product)->with('customSchema', 'ota')->where('id_company',
            auth('web')->user()->id_company)->first()) {
            msg(trans('notification.product.not_found'), 2);
            return redirect()->route('company.product.index');
        }

        $this->initalize($request);
        $product_type = ProductType::where('status', 1)->get();
        // $product_category = \App\Models\ProductCategory::where(['id_company'=>$this->company
        //                                                             ,'status'=>1
        //                                                             ,'id_product_type'=>$product->id_product_type
        //                                                             ])->get();

        $mark = \App\Models\Mark::where('id_company', $this->company)->get();

        $tax = \App\Models\Tax::where('id_company', $this->company)->get();
        if (app()->getLocale() == 'id') {
            $name = 'name_ind';
        } else {
            $name = 'name';
        }
        $product_category = TagProduct::where('status', 1)->orderBy($name, 'asc')->get();
        $product_category_value = TagProductValue::where('product_id', $product->id_product)->get();
        if ($product->city):
        $state = \App\Models\State::where('id_country', $product->city->state->country->id_country)->get();
        $city = \App\Models\City::where('id_state', $product->city->state->id_state)->get();
        else:
            $state = [];
            $city = [];
        endif;
        $custom_schemas = $product->customSchema;
        $units = UnitName::where('is_active', true)->get();

        \JavaScript::put([
            'min_people' => \trans('product_provider.min_people'),
            'min_people_else' => \trans('product_provider.min_people_else'),
            'price_from' => \trans('product_provider.price_from'),
            'price_from_else' => \trans('product_provider.price_from_else'),
        ]);
        // default jika kosong
        if ($custom_schemas->isEmpty()) {
            $custom_schemas = [
                (object)[
                    'id' => null,
                    'type_custom' => 'text',
                    'value' => [null],
                    'label_name' => '',
                    'description' => '',
                    'fill_type' => 'customer'

                ]
            ];
        }
        $insurances = Insurance::active()->get();
        if (auth()->user()->company->is_klhk == 1){
            return view('klhk.dashboard.company.product.edit',
                compact('product_type', 'product', 'product_category', 'tax', 'mark', 'state', 'city', 'custom_schemas','insurances', 'units'));
        }
        return view('dashboard.company.product.edit',
            compact('product_type', 'product', 'product_category', 'tax', 'mark', 'state', 'city', 'custom_schemas','insurances', 'units'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  mixed  $id
     * @param  mixed  $request
     *
     * @return void
     */
    public function update($id, \App\Http\Requests\ProductFormRequest $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $product = Product::find($id);
        $this->initalize($request);
        \DB::transaction(function () use ($product, $request) {

            if ($product->id_company != $this->company) {

                return response()->json([
                    'status' => 400,
                    'message' => 'Action not allowed'
                ]);

            }

            if ($request->input('discount_amount_type') == '1') {
                $rule = [
                    'discount_amount' => 'numeric|max:100|min:0'
                ];
                $this->validate($request, $rule);
            }

            $product->update([
                'product_name' => strip_tags($request->get('product_name')),
                'permalink' => $request->get('permalink'),
                'id_product_category' => null,
                'id_product_type' => $request->get('product_type'),
                'unique_code' => $request->get('unique_code'),
                'id_city' => $request->get('city'),
                'longitude' => $request->get('long'),
                'latitude' => $request->get('lat'),
                'duration' => $request->get('duration'),
                'duration_type' => $request->get('duration_type'),
                'brief_description' => $request->get('brief_description'),
//                            'guide_language'=>$request->get('guide_language'),
                'long_description' => clean($request->get('long_description'), 'simple'),
                'important_notes' => clean($request->get('important_notes'), 'simple'),
                // 'itinerary'=>$request->get('itinerary') ? $request->get('itinerary') : null,
                'min_order' => $request->get('min_order'),
                'max_order' => $request->get('max_order'),
                'min_people' => $request->get('min_people'),
                'max_people' => $request->get('max_people'),
                'quota_type' => $request->filled('quota_type')?$request->input('quota_type'):'periode',
                'updated_by' => $this->user,
                'currency' => $request->get('currency'),
                'advertised_price' => $request->get('advertised_price') ? $request->get('advertised_price') : 0,
                'discount_name' => $request->get('discount_name') ? $request->get('discount_name') : null,
                'discount_amount_type' => strlen($request->get('discount_amount_type')) > 0 ? $request->get('discount_amount_type') : null,
                'discount_amount' => $request->get('discount_amount') ? $request->get('discount_amount') : 0,
                'id_mark' => $request->get('mark') ? $request->get('mark') : null,
                'fee_name' => $request->get('fee_name') ? $request->get('fee_name') : null,
                'fee_amount' => $request->get('fee_amount') ? $request->get('fee_amount') : 0,
                // 'booking_confirmation'=>$request->get('booking_confirmation'),
                'booking_confirmation' => $request->input('booking_confirmation', 1),
                'minimum_notice' => $request->get('minimum_notice'),
                'status' => $request->get('status') ? $request->get('status') : 0,
                'publish' => $request->get('publish'),
                'addon1' => $request->get('addon1') ? $request->get('addon1') : 0,
                'addon2' => $request->get('addon2') ? $request->get('addon2') : 0,
                'addon3' => $request->get('addon3') ? $request->get('addon3') : 0,
                'addon4' => $request->get('addon4') ? $request->get('addon4') : 0,
                'addon5' => $request->get('addon5') ? $request->get('addon5') : 0,
                'addon6' => $request->get('addon6') ? $request->get('addon6') : 0,
                'addon7' => $request->get('addon7') ? $request->get('addon7') : 0,
                'addon8' => $request->get('addon8') ? $request->get('addon8') : 0,
                'availability' => $request->get('availability'),
                'show_exclusion' => $request->has('show_exclusion'),
                'vat'   => $request->has('vat')
            ]);
            $product->languages()->sync($request->input('guide_language'));

            ############## DELETE & INSERT PRICING ###################
            \App\Models\ProductPrice::where('id_product', $product->id_product)->delete();
            $d_price = [];
            $pri = $request->get('price');
            if (isset($pri)) {
                foreach ($request->get('price') as $i => $row) {
                    array_push($d_price, [
                        'id_product' => $product->id_product,
                        'currency' => $request->get('currency'),
                        'unit_name_id' => (int) $request->get('unit_name_id')[0],
                        'price_type' => $request->get('price_type')[$i],
                        'price' => $row,
                        'people' => 1,
                        'price_from' => $request->get('price_from')[$i],
                        'price_until' => $request->get('price_until')[$i],
                    ]);
                }
                \App\Models\ProductPrice::insert($d_price);
            }


            ProductItinerary::where('product_id', $product->id_product)->delete();
            $itinerary = [];
            $it = $request->get('itinerary');
            if (isset($it)) {
                foreach ($request->get('itinerary') as $i => $row) {
                    if (isset($row)) {
                        array_push($itinerary, [
                            'product_id' => $product->id_product,
                            'itinerary' => $row
                        ]);
                    }

                }
            }


            ProductItinerary::insert($itinerary);


            TagProductValue::where('product_id', $product->id_product)->delete();
            if ($request->get('product_category') AND count($request->get('product_category')) > 0) {
                $tag = [];
                foreach ($request->get('product_category') as $i => $row) {
                    array_push($tag, [
                        'product_id' => $product->id_product,
                        'tag_product_id' => $row,
                    ]);
                }

                TagProductValue::insert($tag);
            }
            if ($request->has('insurances') && count($request->get('insurances'))>0){
                $product->insurances()->sync(array_keys($request->get('insurances')));
            }else{
                $product->insurances()->sync([]);
            }


            ################## Delete & INSERT TAXES #################
            \App\Models\ProductTax::where('id_product', $product->id_product)->delete();

            if ($request->get('tax')) {
                $d_data = [];
                foreach ($request->get('tax') as $row) {
                    array_push($d_data, [
                        'id_product' => $product->id_product,
                        'id_tax' => $row,
                    ]);
                }
                \App\Models\ProductTax::insert($d_data);
            }

            ################ INSERT SCHEDULE #################
            //\App\Models\ProductSchedule::where('id_product', $product->id_product)->delete();

            $d_data = [];
            foreach ($request->get('start_date') as $i => $row) {
                ProductSchedule::updateOrCreate([
                    'id_product'    => $product->id_product,
                    'id_schedule'   => $request->input('schedule_id.'.$i)
                ], [
                    'start_date' => Carbon::parse($row)->toDateString(),
                    'end_date' => $product->availability == '0' ? Carbon::parse($row)->toDateString() : Carbon::parse($request->input('end_date.'.$i))->toDateString(),
                    'start_time' => Carbon::parse($request->input('start_time.'.$i))->toTimeString(),
                    'end_time' => Carbon::parse($request->input('end_time.'.$i))->toTimeString(),
                    'sun' => $request->input('sun.'.$i, 0),
                    'mon' => $request->input('mon.'.$i, 0),
                    'tue' => $request->input('tue.'.$i, 0),
                    'wed' => $request->input('wed.'.$i, 0),
                    'thu' => $request->input('thu.'.$i, 0),
                    'fri' => $request->input('fri.'.$i, 0),
                    'sat' => $request->input('sat.'.$i, 0),
                    // 'day_type' => isset($request->get('day_type')[$i]) ? $request->get('day_type')[$i] : 0,
                ]);
                /*array_push($d_data, [
                    'id_product' => $product->id_product,
                    'start_date' => date('Y-m-d', strtotime($row)),
                    'end_date' => $product->availability == '0' ? date('Y-m-d', strtotime($row)) : date('Y-m-d',
                        strtotime($request->get('end_date')[$i])),
                    'start_time' => date('H:i', strtotime($request->get('start_time')[$i])),
                    'end_time' => date('H:i', strtotime($request->get('end_time')[$i])),
                    'sun' => isset($request->get('sun')[$i]) ? $request->get('sun')[$i] : 0,
                    'mon' => isset($request->get('mon')[$i]) ? $request->get('mon')[$i] : 0,
                    'tue' => isset($request->get('tue')[$i]) ? $request->get('tue')[$i] : 0,
                    'wed' => isset($request->get('wed')[$i]) ? $request->get('wed')[$i] : 0,
                    'thu' => isset($request->get('thu')[$i]) ? $request->get('thu')[$i] : 0,
                    'fri' => isset($request->get('fri')[$i]) ? $request->get('fri')[$i] : 0,
                    'sat' => isset($request->get('sat')[$i]) ? $request->get('sat')[$i] : 0,
                    // 'day_type' => isset($request->get('day_type')[$i]) ? $request->get('day_type')[$i] : 0,
                ]);*/
            }

            //\App\Models\ProductSchedule::insert($d_data);

            ################# Remove IMAGE #####################
            if (!empty($request->input('remove_image'))) {
                $items = collect($request->input('remove_image', []))->map(function ($value) {
                    return basename($value);
                })->toArray();

                \App\Models\ProductImage::where('id_product', $product->id_product)
                    ->whereIn('url', $items)
                    ->delete();
            }


            ################# ISERT IMAGE #####################
            if ($request->hasFile('images')) {
                $d_data = [];

                foreach ($request->file('images') as $i => $file) {
                    // ubah file ext jadi jpg
                    $image_name = pathinfo($file->hashName(), PATHINFO_FILENAME).'.jpg';

                    $sizes = [
                        [
                            'width' => 1600,
                            'height' => 900,
                            'path' => public_path('uploads/products')
                        ],
                        [
                            'width' => null,
                            'height' => 200,
                            'path' => public_path('uploads/products/thumbnail')
                        ]
                    ];

                    foreach ($sizes as $size) {
                        File::isDirectory($size['path']) or File::makeDirectory($size['path'], 0777, true, true);
                        Image::make($file)
                            ->rotate(360 - $request->input('crop-images.'.$i.'.rotate'))
                            ->crop((int)$request->input('crop-images.'.$i.'.width'),
                                (int)$request->input('crop-images.'.$i.'.height'),
                                (int)$request->input('crop-images.'.$i.'.x'),
                                (int)$request->input('crop-images.'.$i.'.y'))
                            ->resize($size['width'], $size['height'], function ($constraint) {
                                return $constraint->aspectRatio();
                            })
                            ->encode('jpeg')
                            ->save($size['path'].'/'.$image_name)
                            ->destroy();
                    }

                    $is_primary = $i == 0 ? true : false;
                    array_push($d_data, [
                        'id_product' => $product->id_product,
                        'url' => $image_name,
                        'is_primary' => $is_primary,
                    ]);
                }

                \App\Models\ProductImage::insert($d_data);
            }

            // Update or Create Custom Information
            if (!empty(array_filter($request->get('custom_label')))) {
                $custom = collect(array_filter($request->get('custom_label')))->map(function ($value, $key) use (
                    $request
                ) {
                    $values = null;
                    // Jika tipe adalah choices dan checkbox
                    if (in_array($request->input('custom_type.'.$key), ['choices', 'checkbox', 'dropdown'])) {
                        $values = $request->input('custom_values.'.$key);
                    }

                    return [
                        'id' => $request->input('custom_id.'.$key),
                        'type_custom' => $request->input('custom_type.'.$key),
                        'label_name' => $value,
                        'fill_type' => $request->input('custom_fill_type.'.$key),
                        'description' => $request->input('custom_description.'.$key),
                        'value' => $values
                    ];
                });

                $custom->each(function ($value) use ($product) {
                    $where['product_id'] = $product->id_product;
                    if (!empty($value['id'])) {
                        $where['id'] = $value['id'];
                    } else {
                        $where['label_name'] = $value['label_name'];
                        $where['type_custom'] = $value['type_custom'];
                    }

                    CustomSchema::updateOrCreate($where, [
                        'label_name' => $value['label_name'],
                        'type_custom' => $value['type_custom'],
                        'fill_type' => $value['fill_type'],
                        'description' => $value['description'],
                        'value' => $value['value']
                    ]);
                });
            }
            // End Update or Create Custom Information

            // Delete custom information yang sudah dibutuhkan
            $delete_id = (array)$request->input('custom_remove_id');
            $emptyLabel = collect($request->input('custom_label'))
                ->filter(function ($value, $key) {
                    return $value == null;
                });


            if (!empty($emptyLabel)) {
                foreach ($emptyLabel as $key => $value) {
                    if (!empty($request->input('custom_id.'.$key))) {
                        array_push($delete_id, $request->input('custom_id.'.$key));
                    }
                }
            }

            if (!empty($delete_id)) {
                CustomSchema::destroy($delete_id);
            }
            // END Delete custom information yang sudah dibutuhkan

            // Distribution ota
            // $product->ota()->sync($request->input('ota', []));
            // END Distribution ota
        });

        // return json_encode(['status'=>200,'message'=>'Product Saved','data'=>['url'=>Route('company.product.edit',$product->id_product) ] ]);
        $prefix = $request->isSecure()?'https://':'http://';
        return response()->json([
            'status' => 200,
            'message' => \trans('product_provider.update_submit'),
            'success' => \trans('product_provider.success'),
            'oops' => \trans('general.whoops'),
            'data' => [
                'id' => $product->id_product,
                'url' => Route('company.product.edit', $product->id_product),
                'preview' => $prefix.$product->company->domain_memoria.'/product/detail/'.$product->unique_code
            ]
        ]);

    }

    /**
     * Remove image the specified resource from storage.
     *
     * @param  mixed  $request
     *
     * @return void
     */
    public function delete_image(Request $request)
    {
        $this->initalize($request);

        $image = $request->get('image');
        $product_image = \App\Models\ProductImage::where(['url' => $image])->first();

        if ($product_image->product->id_company != $this->company) {
            return response()->json([
                'status' => 405,
                'message' => 'Action not allowed'
            ]);
        }

        if ($product_image) {
            //$product_image = \App\Models\ProductImage::where(['id_image'=>$product->id_image])->first();

            //Update other image to be primary image
            if ($product_image->is_primary) {

                $other_image = \App\Models\ProductImage::where([
                    ['id_image', '<>', $product_image->id_image],
                    'id_product' => $product_image->id_product
                ])
                    ->first();

                if ($other_image) {
                    $other_image->is_primary = true;
                    $other_image->save();
                }

            }

            $file = $product_image->url;
            $product_image->delete();

            $filename = public_path().'/uploads/products/'.$file;
            \File::delete($filename);

            return response()->json([
                'status' => 200,
                'message' => 'Image Deleted'
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Image not found'
            ]);
        }

    }

    /**
     * function upload image
     *
     * @param  mixed  $request
     *
     * @return void
     */
    public function upload_image(Request $request)
    {
        $this->initalize($request);

        $image = $request->get('image');
        // $image_name = time()."-".$file->getClientOriginalName();
        // $image = \Image::make($file)->resize(null,400,function ($constraint) {
        //         $constraint->aspectRatio();
        //     })->save(public_path('uploads/products/'.$image_name));
        //
        //
        // $image = \Image::make($file)->resize(null, 200,function($constraint){
        //     $constraint->aspectRatio();
        // })->save(public_path('uploads/products/thumbnail/'.$image_name));
        //
        // $is_primary = $i==0 ? true : false;
        // array_push($d_data,[
        //                         'id_product' => $product->id_product,
        //                         'url' => $image_name,
        //                         'is_primary' => $is_primary,
        //                     ]);
        //$product_image = \App\Models\ProductImage::where(['id_image'=>$product->id_image])->first();

        //Update other image to be primary image
        // $file = $product_image->url;
        // $product_image->delete();
        //
        // $filename = public_path().'/uploads/products/'.$file;
        // \File::delete($filename);

        return response()->json([
            'status' => 200,
            'data' => $image
        ]);

    }

    /**
     * function search product data
     *
     * @param  mixed  $request
     *
     * @return void
     */
    public function search(Request $request)
    {
        $this->initalize($request);
        $product = Product::where(['id_company' => $this->company, 'status' => true])->OrderBy('created_at',
            'desc')->skip(0)->take(12)->get();
        if (auth()->user()->company->is_klhk == 1){
            $view = view('klhk.dashboard.company.product.search', ['product' => $product]);
        }
        $view = view('dashboard.company.product.search', ['product' => $product]);
        return response()->json([
            'status' => 200,
            'message' => 'Ok',
            'data' => $view->render(),
        ]);
    }


    /**
     * function find products data
     *
     * @param  mixed  $request
     *
     * @return void
     */
    public function find_products(Request $request)
    {
        $this->initalize($request);

        $product_name = $request->get('product_name');
        $category = $request->get('category');

        $where = [];
        array_push($where, ['id_company', '=', $this->company]);

        array_push($where, ['status', '=', true]);


        if (!empty($category)) {
            array_push($where, ['id_product_category', '=', $category]);
        }

        array_push($where, ['product_name', 'LIKE', '%'.$product_name.'%']);


        $product = Product::where($where)->skip(0)->take(10)->get();

        $d_data = [];
        foreach ($product as $row) {
            array_push($d_data, ['value' => $row->product_name, 'data' => $row->id_product]);
        }
        return response()->json([
            'suggestions' => $d_data
        ]);

    }

    public function duplicateForm(Request $request, $id_product)
    {
        if (!$product = Product::where('id_product', $id_product)->with('customSchema', 'ota')->where('id_company',
            auth('web')->user()->id_company)->first()) {
            msg(trans('notification.product.not_found'), 2);
            return redirect()->route('company.product.index');
        }

        $this->initalize($request);
        $product_type = ProductType::where('status', 1)->get();
        // $product_category = \App\Models\ProductCategory::where(['id_company'=>$this->company
        //                                                             ,'status'=>1
        //                                                             ,'id_product_type'=>$product->id_product_type
        //                                                             ])->get();

        $mark = \App\Models\Mark::where('id_company', $this->company)->get();

        $tax = \App\Models\Tax::where('id_company', $this->company)->get();
        if (app()->getLocale() == 'id') {
            $name = 'name_ind';
        } else {
            $name = 'name';
        }
        $product_category = TagProduct::where('status', 1)->orderBy($name, 'asc')->get();
        $product_category_value = TagProductValue::where('product_id', $product->id_product)->get();
        $state = \App\Models\State::where('id_country', $product->city->state->country->id_country)->get();
        $city = \App\Models\City::where('id_state', $product->city->state->id_state)->get();
        $custom_schemas = $product->customSchema;

        \JavaScript::put([
            'min_people' => \trans('product_provider.min_people'),
            'min_people_else' => \trans('product_provider.min_people_else'),
            'price_from' => \trans('product_provider.price_from'),
            'price_from_else' => \trans('product_provider.price_from_else'),
        ]);
        // default jika kosong
        if ($custom_schemas->isEmpty()) {
            $custom_schemas = [
                (object)[
                    'id' => null,
                    'type_custom' => 'text',
                    'value' => [null],
                    'label_name' => '',
                    'description' => '',
                    'fill_type' => 'customer'

                ]
            ];
        }
        $insurances = Insurance::active()->get();
        $units = UnitName::where('is_active', true)->get();

        if (auth()->user()->company->is_klhk == 1){
            return view('klhk.dashboard.company.product.duplicate',
                compact('product_type', 'product', 'product_category', 'tax', 'mark', 'state', 'city', 'custom_schemas','insurances', 'units'));
        }
        return view('dashboard.company.product.duplicate',
            compact('product_type', 'product', 'product_category', 'tax', 'mark', 'state', 'city', 'custom_schemas','insurances', 'units'));
    }

    public function duplicateProduct($id, \App\Http\Requests\ProductFormRequest $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $this->initalize($request);

        if (!$product = Product::whereIdProduct($id)->where('id_company', auth()->user()->id_company)->first()) {
            return response()->json([
                'status' => 400,
                'message' => 'Action not allowed'
            ]);
        }
        try {
            \DB::beginTransaction();
            $product_type = $request->get('product_type');
            $product_category = $request->get('product_category');
            $unique_code = $request->get('unique_code');

            if (empty($unique_code)) {
                if (isset($product_category) AND count($product_category) > 0) {
                    $c = $product_category[0];
                } else {
                    $c = $product_category;
                }
                $unique_code = 'SKU'.$this->company.$product_type.$c.str_replace('.', '', microtime(true));
            }

            if ($request->input('discount_amount_type') == '1') {
                $rule = [
                    'discount_amount' => 'numeric|max:100|min:0'
                ];
                $this->validate($request, $rule);
            }
            if (!$request->has('start_date')){
                return apiResponse(422,__('validation.invalid_data'),null,['start_date[]'=>[__('validation.required',['attribute'=>'Start Date'])]]);
            }

            $newproduct = Product::create([
                'product_name' => strip_tags($request->get('product_name')),
                'permalink' => $request->get('permalink'),
                'id_product_category' => null,
                'id_product_type' => $product_type,
                'unique_code' => $unique_code,
                'id_city' => $request->get('city'),
                'duration' => $request->get('duration'),
                'duration_type' => $request->get('duration_type'),
                'longitude' => $request->get('long'),
                'latitude' => $request->get('lat'),
                'brief_description' => $request->get('brief_description'),
//                'guide_language'=>$request->get('guide_language'),
                'long_description' => clean($request->get('long_description'), 'simple'),
                'important_notes' => clean($request->get('important_notes'), 'simple'),
                // 'itinerary'=>$request->get('itinerary') ? $request->get('itinerary') : null,
                'min_order' => $request->get('min_order'),
                'max_order' => $request->get('max_order'),
                'min_people' => $request->get('min_people'),
                'max_people' => $request->get('max_people'),
                'quota_type' => $request->filled('quota_type')?$request->input('quota_type'):'periode',
                'created_by' => $this->user,
                'updated_by' => $this->user,
                'currency' => $request->get('currency'),
                'advertised_price' => $request->get('advertised_price'),
                'discount_name' => $request->get('discount_name') ? $request->get('discount_name') : null,
                'discount_amount_type' => strlen($request->get('discount_amount_type')) > 0 ? $request->get('discount_amount_type') : null,
                'discount_amount' => $request->get('discount_amount') ? $request->get('discount_amount') : 0,
                'id_mark' => $request->get('mark') ? $request->get('mark') : null,
                'fee_name' => $request->get('fee_name') ? $request->get('fee_name') : null,
                'fee_amount' => $request->get('fee_amount') ? $request->get('fee_amount') : 0,
                // 'booking_confirmation'=> $request->get('booking_confirmation') ? $request->get('booking_confirmation') : 0,
                'booking_confirmation' => $request->input('booking_confirmation', 1),
                'minimum_notice' => $request->get('minimum_notice'),
                'id_company' => $this->company,
                'status' => $request->get('status') ? $request->get('status') : 0,
                'publish' => $request->get('publish') ? $request->get('publish') : 0,
                'addon1' => $request->get('addon1') ? $request->get('addon1') : 0,
                'addon2' => $request->get('addon2') ? $request->get('addon2') : 0,
                'addon3' => $request->get('addon3') ? $request->get('addon3') : 0,
                'addon4' => $request->get('addon4') ? $request->get('addon4') : 0,
                'addon5' => $request->get('addon5') ? $request->get('addon5') : 0,
                'addon6' => $request->get('addon6') ? $request->get('addon6') : 0,
                'addon7' => $request->get('addon7') ? $request->get('addon7') : 0,
                'addon8' => $request->get('addon8') ? $request->get('addon8') : 0,
                'availability' => $request->get('availability'),
                'show_exclusion' => $request->has('show_exclusion'),
                'vat'   => $request->has('vat')
            ]);
            $newproduct->languages()->sync($request->input('guide_language'));

            ############## INSERT PRICING ###################
            $d_price = [];
            foreach ($request->get('price') as $i => $row) {
                array_push($d_price, [
                    'id_product' => $newproduct->id_product,
                    'currency' => $request->get('currency'),
                    'unit_name_id' => (int) $request->get('unit_name_id')[0],
                    'price_type' => $request->get('price_type')[$i],
                    'price' => $row,
                    'price_from' => $request->get('price_from')[$i],
                    'price_until' => $request->get('price_until')[$i],
                ]);
            }

            \App\Models\ProductPrice::insert($d_price);

            $itinerary = [];
            foreach ($request->get('itinerary') as $i => $row) {
                if (isset($row)) {
                    array_push($itinerary, [
                        'product_id' => $newproduct->id_product,
                        'itinerary' => $row
                    ]);
                }

            }

            ProductItinerary::insert($itinerary);


            $tag = [];
            if ($request->get('product_category') AND count($request->get('product_category')) > 0) {
                foreach ($request->get('product_category') as $i => $row) {
                    array_push($tag, [
                        'product_id' => $newproduct->id_product,
                        'tag_product_id' => $row,
                    ]);
                }

                TagProductValue::insert($tag);
            }

            ################## INSERT TAXES #################
            if ($request->get('tax')) {
                $d_data = [];
                foreach ($request->get('tax') as $row) {
                    array_push($d_data, [
                        'id_product' => $newproduct->id_product,
                        'id_tax' => $row,
                    ]);
                }
                \App\Models\ProductTax::insert($d_data);
            }

            ################ INSERT SCHEDULE #################
            $d_data = [];
            foreach ($request->get('start_date') as $i => $row) {
                array_push($d_data, [
                    'id_product' => $newproduct->id_product,
                    'start_date' => date('Y-m-d', strtotime($row)),
                    'end_date' => $newproduct->availability == '0' ? date('Y-m-d', strtotime($row)) : date('Y-m-d',
                        strtotime($request->get('end_date')[$i])),
                    'start_time' => date('H:i', strtotime($request->get('start_time')[$i])),
                    'end_time' => date('H:i', strtotime($request->get('end_time')[$i])),
                    'sun' => isset($request->get('sun')[$i]) ? $request->get('sun')[$i] : 0,
                    'mon' => isset($request->get('mon')[$i]) ? $request->get('mon')[$i] : 0,
                    'tue' => isset($request->get('tue')[$i]) ? $request->get('tue')[$i] : 0,
                    'wed' => isset($request->get('wed')[$i]) ? $request->get('wed')[$i] : 0,
                    'thu' => isset($request->get('thu')[$i]) ? $request->get('thu')[$i] : 0,
                    'fri' => isset($request->get('fri')[$i]) ? $request->get('fri')[$i] : 0,
                    'sat' => isset($request->get('sat')[$i]) ? $request->get('sat')[$i] : 0,
                    // 'day_type' => isset($request->get('day_type')[$i]) ? $request->get('day_type')[$i] : 0,
                ]);
            }

            \App\Models\ProductSchedule::insert($d_data);

            ################# ISERT IMAGE #####################
            if ($request->hasFile('images')) {
                $d_data = [];
                foreach ($request->file('images') as $i => $file) {
                    // ubah file ext jadi jpg
                    $image_name = pathinfo($file->hashName(), PATHINFO_FILENAME).'.jpg';

                    $sizes = [
                        [
                            'width' => 1600,
                            'height' => 900,
                            'path' => public_path('uploads/products')
                        ],
                        [
                            'width' => null,
                            'height' => 200,
                            'path' => public_path('uploads/products/thumbnail')
                        ]
                    ];

                    foreach ($sizes as $size) {
                        File::isDirectory($size['path']) or File::makeDirectory($size['path'], 0777, true, true);
                        Image::make($file)
                            ->rotate(360 - $request->input('crop-images.'.$i.'.rotate'))
                            ->crop((int)$request->input('crop-images.'.$i.'.width'),
                                (int)$request->input('crop-images.'.$i.'.height'),
                                (int)$request->input('crop-images.'.$i.'.x'),
                                (int)$request->input('crop-images.'.$i.'.y'))
                            ->resize($size['width'], $size['height'], function ($constraint) {
                                return $constraint->aspectRatio();
                            })
                            ->encode('jpeg')
                            ->save($size['path'].'/'.$image_name)
                            ->destroy();
                    }

                    $is_primary = $i == 0 ? true : false;

                    array_push($d_data, [
                        'id_product' => $newproduct->id_product,
                        'url' => $image_name,
                        'is_primary' => $is_primary,
                    ]);
                }

                \App\Models\ProductImage::insert($d_data);
            }

            $id = $newproduct->id_product;

            // Custom Information
            if (!empty(array_filter($request->get('custom_label')))) {
                $custom = collect(array_filter($request->get('custom_label')))->map(function ($value, $key) use (
                    $request
                ) {
                    $values = null;
                    // Jika tipe adalah choices dan checkbox
                    if (in_array($request->input('custom_type.'.$key), ['choices', 'checkbox', 'dropdown'])) {
                        $values = $request->input('custom_values.'.$key);
                    }

                    return [
                        'type_custom' => $request->input('custom_type.'.$key),
                        'label_name' => $value,
                        'fill_type' => $request->input('custom_fill_type.'.$key),
                        'description' => $request->input('custom_description.'.$key),
                        'value' => $values
                    ];
                });

                $newproduct->customSchema()->createMany($custom->toArray());
            }
            \DB::commit();
            $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
            $newCompany = $product->company;
            $loc = \Stevebauman\Location\Facades\Location::get($ip);
            $http = env('HTTPS', false) == true ? 'https://' : 'http://';
            $content = '**New Product ' . Carbon::now()->format('d M Y H:i:s') . '**';
            $content .= '```';
            $content .= "Company Name    : " . $newCompany->company_name . "\n";
            $content .= "Domain Gomodo   : " . $http . $newCompany->domain_memoria . "\n";
            $content .= "Product Name    : " . $newproduct->product_name. "\n";
            $content .= "URL Product     : " . $http.$newCompany->domain_memoria.'/product/detail/'.$product->unique_code. "\n";
            $content .= "IP Address      : " . $ip. "\n";
            $content .= "City Name       : " . $loc->cityName. "\n";
            $content .= "Region Name     : " . $loc->regionName. "\n";
            $content .= "Country Code    : " . $loc->countryCode. "\n";
            $content .= "Pricing         : \n\n";
            foreach ($product->pricing as $price) {
            $content .= $price->price_from." - ".$price->price_until." (".$price->unit->name.")     :  ".format_priceID($price->price,
                        $price->currency). "\n";
            }
            $content .= '```';

            $this->sendDiscordNotification(sprintf('%s',$content), 'product');

            return json_encode([
                'status' => 200,
                'message' => \trans('product_provider.store_submit'),
                'success' => \trans('product_provider.success'),
                'oops' => \trans('general.whoops'),
                'data' => [
                    'id' => $id,
                    'url' => Route('company.product.edit', $newproduct->id_product),
                    'preview' => str_replace(env('APP_URL'), $newproduct->company->domain_memoria,
                        route('product.detail', ['slug' => $newproduct->unique_code]))
                ]
            ]);


        } catch (\Exception $exception) {
            \DB::rollBack();
            return apiResponse(500, __('general.whoops'), getException($exception));

        }
    }

    public function exportOrders(Request $request)
    {
        $id_product = $request->input('id');
        $product = Product::select('product_name')
            ->where([
                'id_company'    => $request->user()->id_company,
                'id_product'    => $id_product
            ])
            ->firstOrFail();

        $file_name = trans('product_provider.export_order.file.name').' '.str_replace(str_split('*."/\[]:;|,<>'), ' ', $product->product_name) .' '.now()->format('d-M-Y H-i');
        if ($request->input('paid_only')) {
            $file_name .= ' '.trans('product_provider.export_order.file.paid');
        }
        return (new CompanyProductExport((int) $id_product, $request->input('paid_only', false)))
            ->download($file_name.'.xls');
    }

}
