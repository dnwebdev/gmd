<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TagProductValue;
use App\Models\TagProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendEmail;
use Mail;

class HomeController extends Controller
{
    var $company = 0;
    var $d_theme = null;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('company');
        //echo 'autenticated';exit;
    }


    /**
     * Function initalize get data user
     *
     * @param  mixed $request
     *
     * @return void
     */
    private function initialize(Request $request)
    {
        $this->company = $request->get('my_company');

        $theme = \App\Models\CompanyTheme::where('id_company', $this->company)->first();
        $this->d_theme = $theme;
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
        $this->initialize($request);

        if ($this->company == 0) {
            return view('public.memoria.home');
        } else {
            $banner = \App\Models\WebsiteBanner::where(['id_company' => $this->company, 'status' => true])->get();
            $newproduct = \App\Models\Product::where(['id_company' => $this->company, 'status' => true, 'publish' => true])->orderBy('created_at', 'desc')->skip(0)->take(3)->get();
            $totalProduct = \App\Models\Product::where(['id_company' => $this->company, 'status' => true, 'publish' => true])->orderBy('created_at', 'desc')->count();
            $hasMore = false;
            if ($totalProduct > 3) {
                $hasMore = true;
            }

            $popularproduct = \App\Models\Product::where(['id_company' => $this->company, 'status' => true, 'publish' => true])->orderBy('sold', 'desc')->orderBy('viewed', 'desc')->orderBy('created_at', 'desc')->skip(0)->take(3)->get();
            $company = \App\Models\Company::find($this->company);

            $product_type = \App\Models\ProductType::where('status', true)->get();

            $destination = \App\Models\Product::where('id_company', $this->company)->select('id_city')->with('city')->groupBy('id_city')->get();


            return view('public.agents.admiria.index', [
                'd_theme' => $this->d_theme,
                'product_type' => $product_type,
                'banner' => $banner,
                'newproduct' => $newproduct,
                'popularproduct' => $popularproduct,
                'destination' => $destination,
                'totalProduct'=>$totalProduct,
                'hasMore'=>$hasMore,
                'company' => $company
            ]);
        }
    }

    /**
     * function detail product
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function detail(Request $request)
    {

        $this->initialize($request);
        $id = explode('-', $request->segment(2));
        $id = $id[0];
        return redirect()->route('product.detail',['slug'=>$id]);
//        if (!empty($id)) {
//
////            $product = \App\Models\Product::where(['id_company' => $this->company, 'unique_code' => $id, 'status' => true])->first();
//            $product = \App\Models\Product::where(['id_company' => $this->company, 'unique_code' => $id])->first();
//            if (!$product){
//                \Session::flash('error_message',trans('notification.product.not_found'));
//                return redirect()->route('memoria.home');
//            }
//            $company = \App\Models\Company::find($this->company);
//
//            $product_pricing = \App\Models\ProductPrice::where(['id_product' => $product->id_product])->get();
//
//            $similaritem = \App\Models\Product::where([
//                ['id_company', '=', $this->company],
//                ['id_product_category', '=', $product->id_product_category],
//                ['id_product', '<>', $product->id_product],
//                ['status', '=', true]
//            ])->orderBy('created_at', 'desc')->skip(0)->take(3)->get();
//
//            //AddLogs
//            $product_service = app('\App\Services\ProductService');
//            $product_service->add_log($product);
//            $product_category = TagProduct::where('status', 1)->get();
//            $product_category_value = TagProductValue::where('product_id', $product->id_product)->get();
//
//            return view('public.agents.admiria.detail', ['d_theme' => $this->d_theme, 'product' => $product, 'similaritem' => $similaritem, 'company' => $company, 'product_category' => $product_category, 'category_tag_value' => $product_category_value, 'product_pricing' => $product_pricing]);
//        }
    }

    /**
     * function search products
     *
     * @param  mixed $request
     * @param  mixed $slug
     *
     * @return void
     */
    public function products(Request $request, $slug = '')
    {
        $this->initialize($request);

        $find = [['id_company', '=', $this->company], ['publish', '=', true]];

        if ($request->get('product_type')) {
            array_push($find, ['id_product_type', '=', $request->get('product_type')]);
        }

        if ($request->get('city')) {
            array_push($find, ['id_city', '=', $request->get('city')]);
        }

        if ($request->get('keyword')) {
            array_push($find, ['product_name', 'like', '%' . $request->get('keyword') . '%']);
        }


        $products = \App\Models\Product::where($find);
        $company = \App\Models\Company::find($this->company);

        if ($request->get('keyword')) {
            $keyword = $request->get('keyword');

            $products = $products->whereHas('city', function ($q) use ($keyword) {
                $q->where([['product_name', 'like', '%' . $keyword . '%']]);
            });
        }

        if ($request->get('month')) {
            $month = $request->get('month');
            $products = $products->whereHas('schedule', function ($q) use ($month) {
                // $q->whereRaw("'".$schedule."' between `start_date` and `end_date`");
                $q->whereRaw(" ? between MONTH(start_date) and MONTH(end_date) ", [$month]);
            });
        }

        $order_by = $request->get('order_by');
        $order = $request->get('order');
        if (!empty($order_by) && !empty($order) && ($order == 'desc' || $order == 'asc')) {
            if ($order_by == 'price') {
                $products = $products->orderBy('advertised_price', $order);
            } else if ($order_by == 'name') {
                $products = $products->orderBy('product_name', $order);
            }

        }

        if ($request->get('find')) {
            $reqfind = $request->get('find');
            if ($reqfind == 'popular-destination') {
                $products = $products->orderBy('sold', 'desc')->orderBy('viewed', 'desc');
            }
        }


        $products = $products->orderBy('created_at', 'desc')->paginate(12)->appends($request->all());
        $destination = \App\Models\Product::where('id_company', $this->company)->select('id_city')->with('city')->groupBy('id_city')->get();

        return view('public.agents.admiria.products', ['d_theme' => $this->d_theme, 'city' => $request->get('city'), 'products' => $products, 'company' => $company, 'destination' => $destination]);

    }

    /**
     * function validate schedule product
     *
     * @param  mixed $request
     * @param  mixed $product_service
     *
     * @return void
     */
    public function validate_schedule(Request $request, \App\Services\ProductService $product_service)
    {
        $this->initialize($request);
        $unique_code = $request->get('product');
        $m_product = \App\Models\Product::where(['unique_code' => $unique_code
            , 'id_company' => $this->company])->first();
        if (!$m_product) {
            return json_encode(['status' => 404, 'message' => 'Product is invalid.']);
        }

        $request->request->set('product', $m_product->id_product);


        return $product_service->validate_schedule($this->company);
    }

    /**
     * function booking product
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function book(Request $request)
    {
        if ($request->getMethod() == 'GET') {
            return redirect()->route('memoria.more');
        }
        $this->initialize($request);

        $adult = $request->get('adult') ? $request->get('adult') : 1;
        $children = $request->get('children') ? $request->get('children') : 0;
        $infant = $request->get('infant') ? $request->get('infant') : 0;
        $unique_code = $request->get('product');
        $schedule = $request->get('schedule');
        $company = \App\Models\Company::find($this->company);

        $product = \App\Models\Product::where(['unique_code' => $unique_code, 'id_company' => $this->company, 'status' => true, 'publish' => true])->first();
        $request->request->set('product', $product->id_product);


        $product_service = app('\App\Services\ProductService');

        $res = json_decode($product_service->validate_schedule($this->company));
        if ($res->status != 200) {
            //print_r($res);exit;
            return response()->json($res);
        } else {
            $data = ['product' => $product,
                'schedule' => $schedule,
                'adult' => $adult,
                'children' => $children,
                'infant' => $infant,
                'pricing' => $res->data,
                'd_theme' => $this->d_theme,
            ];

            // print_r($data);
            return view('public.agents.admiria.booking', $data, ['company' => $company]);
        }


    }

    /**
     * inquiry
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function inquiry(Request $request)
    {
        if ($request->getMethod() == 'GET') {
            return redirect()->route('memoria.more');
        }
        
        $this->initialize($request);

        $unique_code = $request->get('product');
        $company = \App\Models\Company::find($this->company);
        $product = \App\Models\Product::where(['unique_code' => $unique_code, 'id_company' => $this->company])->first();

        $data = ['product' => $product];
        return view('public.agents.admiria.inquiry', $data, ['company' => $company]);
    }

    /**
     * function send email special request
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function send_inquiry(Request $request)
    {
        $this->initialize($request);

        $unique_code = $request->get('product');
        $company = \App\Models\Company::find($this->company);

        $product = \App\Models\Product::where(['unique_code' => $unique_code, 'id_company' => $this->company, 'status' => true, 'publish' => true])->first();

        $name = $request->get('name');
        $email = $request->get('email');
        $phone = $request->get('phone');
        $notes = $request->get('notes');
        $schedule = $request->get('schedule');
        $total_guest = $request->get('total_guest');
        $payment = $request->get('payment');
        $notes = $request->get('notes');


        $data = [
            'company_name' => $company->company_name,
            'company_email' => $company->email_company,
            'company_phone' => $company->phone_company,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'product_name' => $product->product_name,
            'schedule' => $schedule,
            'guest' => $total_guest,
            'payment' => $payment,
            'notes' => $notes,
        ];

        $template = view('public.agents.admiria.inquiry_mail', $data)->render();
        $template_customer = view('public.agents.admiria.inquiry_mail_customer', $data)->render();
        dispatch(new SendEmail('Special Inquiry: ' . $product->product_name, $company->email_company, $template));
        dispatch(new SendEmail('Special Inquiry: ' . $product->product_name, $email, $template_customer));

        return response()->json([
            'status' => 200,
            'message' => 'Special Request Submitted'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  mixed $request
     * @param  mixed $product_service
     *
     * @return void
     */
    public function create_order(Request $request, \App\Services\ProductService $product_service)
    {
        $this->initialize($request);

        $unique_code = $request->get('product');
        $m_product = \App\Models\Product::where(['unique_code' => $unique_code
            , 'id_company' => $this->company])->first();


        if (!$m_product) {
            return response()->json([
                'status' => 404,
                'message' => 'Product is invalid'
            ]);
        }

        $request->request->set('product', $m_product->id_product);

        if ($m_product->booking_confirmation == 1) {
            $request->request->set('status', 0);
        } else {
            $request->request->set('status', 8);
        }

        $request->request->set('transaction_type', 0);
        try {
            \DB::beginTransaction();
            $order = json_decode($product_service->make_order(true));

            if ($order->status != 200) {
                \DB::rollback();
                return response()->json($order);
            }

            $order = \App\Models\Order::find($order->data->invoice);

            $request->request->set('invoice', $order->invoice_no);
            $request->request->set('schedule', date('m/d/Y', strtotime($order->order_detail->schedule_date)));
            $request->request->set('phone', $order->customer_info->phone);

            if ($m_product->booking_confirmation == 1) {
                $xendit = app('\App\Services\XenditService');
                $service = json_decode($xendit->make_invoice($order->invoice_no));
                if ($service->status !== 200) {
                    \DB::rollback();
                    return response()->json(['message' => $service->message], $service->status);
                }
                \DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => 'Booking Successful',
                    'data' => ['xendit_url' => $service->data->invoice_url]
                ]);

            } else {
                $company = \App\Models\Company::find($this->company);
                \DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => 'Booking Inquiry Made',
                    'data' => ['url' => $company->domain_memoria]
                ]);
            }
        }catch (\Exception $exception){
            \DB::rollback();
            return response()->json(['message'=>$exception->getMessage()],500);
        }
    }

    /**
     * see retrieve booking
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function retrieve(Request $request)
    {
        $this->initialize($request);

        $company = \App\Models\Company::find($this->company);
        return view('public.agents.admiria.retrieve', ['d_theme' => $this->d_theme, 'company' => $company]);
    }

    /**
     * function process retrieve booking
     *
     * @return void
     */
    public function process_retrieve()
    {
        $request = app('\App\Http\Requests\RetrieveFormRequest');
        $this->initialize($request);

        $invoice = $request->get('invoice');
        $schedule = $request->get('schedule');

//        dd($this->company);
        $order = \App\Models\Order::where('invoice_no',$invoice)->where('id_company',$this->company)->first();
//        dd($order);
        // $order = \App\Models\Order::where('invoice_no',$invoice)->whereHas('order_detail'
        //             ,function($q) use($request){
        //                 $q->where('schedule_date',date('Y-m-d',strtotime($request->get('schedule'))));
        //             })->whereHas('customer_info',function($q) use($request){
        //                 $utility = app('\App\Services\UtilityService');
        //                 $phone = $utility->format_phone($request->get('phone'));
        //
        //                 $q->where('phone',$phone);
        //             })->first();

        // return response()->json([
        //                     'status' => 200,
        //                     'message' => 'OK',
        //                     'data' => $order,
        //                     'second' => $order->order_detail
        //                 ]);

        if ($order) {
            $m_product = \App\Models\Product::where(['id_product' => $order->order_detail->id_product
                , 'id_company' => $order->id_company])->first();
            $company = \App\Models\Company::find($order->id_company);
            return view('public.agents.admiria.booking_info', ['d_theme' => $this->d_theme, 'company' => $company, 'order' => $order]);


            $view = view('public.agents.admiria.booking_info', ['d_theme' => $this->d_theme, 'company' => $company, 'order' => $order])->render();

            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'data' => ['view' => $view]
            ]);

        } else {
            \Session::flash('error','Booking Not Found');
            return redirect()->route('memoria.retrieve');
            return response()->json([
                'status' => 404,
                'message' => 'Booking Not Found',
            ]);
        }


    }

    /**
     * function process send email invoice
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function send_invoice_mail(Request $request)
    {
        $this->initialize($request);
        $email = $request->get('email');
        $invoice = $request->get('invoice');

        $product_service = app('\App\Services\ProductService');
        $product_service->allMailCustomer($this->company, $invoice);
        $product_service->sendWACustomer( $invoice);

//        $utility = app('\App\Services\ProductService');
//        $utility->send_invoice($this->company, $invoice);
    }

    /**
     * function test check email
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function email(Request $request)
    {
        $this->initialize($request);
        $invoice = 'INV22311512796152514';
        $order = \App\Models\Order::find($invoice);
        $company = \App\Models\Company::find($this->company);
        return view('public.agents.admiria.booking-email', ['company' => $company, 'order' => $order]);
    }

    /**
     * function send email
     *
     * @return void
     */
    public function send_email()
    {
        $url = 'https://api.sendgrid.com/v3';
        $request = $url . 'api/mail.send.json';

        $user = 'ZkYfiiAjT6C-ynsSOb9E9A';
        $userkey = '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';

        $session = curl_init($request);
        // Tell curl to use HTTP get
        curl_setopt($session, CURLOPT_POST, FALSE);
        // Tell curl that this is the body of the GET
        curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($session, CURLOPT_USERPWD, $user . ':' . $userkey);
        // Tell curl not to return headers, but do return the response
        curl_setopt($session, CURLOPT_HEADER, False);
        curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        // Tell PHP not to use SSLv3 (instead opting for TLS)
        curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

        // obtain response
        $response = curl_exec($session);
        var_dump($response);
        curl_close($session);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Symfony\Component\HttpFoundation\Response
     */
    public function test_api_mail(Request $request)
    {
        $this->initialize($request);
        return response("OK");
    }

}
