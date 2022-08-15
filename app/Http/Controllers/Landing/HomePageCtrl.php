<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\City;
use App\Models\ProductCategory;
use App\Models\TagProduct;
use App\Models\TagProductValue;
use App\Models\SeoPage;
use App\Models\State;
use Illuminate\Http\Request;

class HomePageCtrl extends Controller
{
    var $company = 0;
    var $d_theme = null;

    public function __construct()
    {
       $this->middleware('company');
        //echo 'autenticated';exit;
	
		
    }


    /**
     * @param Request $request
     */
    private function initialize(Request $request)
    {
       $this->company = $request->get('my_company');

        $theme = \App\Models\CompanyTheme::where('id_company', $this->company)->first();
        $this->d_theme = $theme;
    }

    public function index(Request $request)
    {
		
        $host = $request->getHttpHost();
        if (env('APP_URL') != env('B2B_DOMAIN')):
            if ($host == env('APP_URL')) :
                return redirect(($request->isSecure() ? 'https://' : 'http://') . env('B2B_DOMAIN'));
            endif;
        endif;
        if ($host == env('B2B_DOMAIN')) {
            return view('landing.index');
        }
        $this->initialize($request);
        toastr();
        $orderAds = null;
        if ($this->company != 0) {
            $company = Company::where('id_company', $this->company)->with
            (['products'])->first();
            if ($company) {
              

                $products = [];
                $p = [];
				
				
                $total_product = Product::availableQuota()
                    ->where([
                        'publish' => 1,
                        'status' => 1,
                        'booking_type' => 'online',
                        'id_company' => $this->company
                    ])
                    ->count();

                if ($total_product > 3) {
                    $products = Product::where([
                        'publish' => 1,
                        'status' => 1,
                        'booking_type' => 'online',
                        'id_company' => $this->company
                    ])
                        ->availableQuota()
                        ->withCount('order_detail')
                        ->orderBy('order_detail_count', 'desc')
                        ->orderBy('viewed', 'desc')
                        ->limit(3)
                        ->get();

                    $p = $products->pluck('id_product')->toArray();
                }
                \JavaScript::put([
                    'show_google_widget_reviews' => __('customer.home.show_google_review'),
                    'hide_google_widget_reviews' => __('customer.home.hide_google_review')
                ]);

				
				//$katProduk = ProductCategory::select('id_category','category_name' )->where('id_company', '=',$this->company)->get();
				
				//Kategori ini diambil dari tag produk
				$katProduk = DB::table('tbl_tag_products')->select('tbl_tag_products.id', 'tbl_tag_products.name_ind')->distinct()
							->join('tbl_tag_products_value', 'tbl_tag_products_value.tag_product_id','=', 'tbl_tag_products.id')
							->join('tbl_product', 'tbl_tag_products_value.product_id', '=', 'tbl_product.id_product')	
							->where('tbl_tag_products.status','1')							
							->where('id_company', '=',$this->company)
							->orderBy('tbl_tag_products.name_ind')->get();
							
				if($request->has('kategori'))
					$urlKat = explode(",",$request->query('kategori'));
				else
				    $urlKat = array();
					
				/*if($request->has('tag'))
					$urlTag = explode(",",$request->query('tag'));
				else
				    $urlTag = array();
					
				if($request->has('keyword'))
					$urlKeyword = $request->query('keyword');
				else
					$urlKeyword='';*/
				if($request->has('prov'))
					$urlProv = $request->query('prov');
				else
					$urlProv='';
					
				if($request->has('city'))
					$urlCity = $request->query('city');
				else
					$urlCity='';
					
				if($request->has('sort'))
					$urlSort = $request->query('sort');
				else
					$urlSort='cheapest';
					
					
				
					
				
					

                if ($request->get('klhk') == true)
                    return view('klhk.customer.home.index', compact('company', 'orderAds', 'products', 'p'));
               // return view('customer.home.index', compact('company', 'orderAds', 'products', 'p', 'katProduk','tagProduk', 'urlKeyword','urlKat', 'urlTag','urlSort'));
			   
			    return view('customer.home.index', compact('company', 'orderAds', 'products', 'p', 'katProduk','urlKat','urlProv', 'urlCity','urlSort'));
            }
        }
        $data['top_products'] = OrderDetail::selectRaw('tbl_product.*,COUNT(tbl_order_detail.id) AS total')
            ->whereHas('product', function ($q) {
                $q->availableQuota();
            })
            ->join('tbl_product', 'tbl_order_detail.id_product', '=', 'tbl_product.id_product')
            ->join('tbl_company', 'tbl_company.id_company', '=', 'tbl_product.id_company')
            ->join('tbl_city', 'tbl_city.id_city', '=', 'tbl_order_detail.id_city')
            ->where('tbl_product.publish', 1)
            ->where('tbl_product.status', 1)
            ->where('tbl_product.deleted_at', null)
            ->where('tbl_order_detail.created_at', '>', '2019-03-01 00:00:00')
            ->where('tbl_company.status', 1);

        $data['recommended_products'] = Product::whereHas('company', function ($company) {
            $company->where('verified_provider', 1);
        })->where('tbl_product.publish', 1)->where('tbl_product.status',
            1)->availableQuota()->inRandomOrder();

        $data['top_cities'] = OrderDetail::selectRaw('tbl_city.id_city,
        tbl_city.city_name,tbl_city.city_name_en,
        tbl_city.city_image,COUNT(tbl_order_detail.id) AS total')
            ->whereHas('product', function ($q) {
                $q->availableQuota();
            })
            ->join('tbl_product', 'tbl_order_detail.id_product', '=', 'tbl_product.id_product')
            ->join('tbl_company', 'tbl_company.id_company', '=', 'tbl_product.id_company')
            ->join('tbl_city', 'tbl_city.id_city', '=', 'tbl_order_detail.id_city')
            ->where('tbl_product.deleted_at', null)
            ->where('tbl_order_detail.created_at', '>', '2019-03-01 00:00:00');

        $data['top_states'] = OrderDetail::selectRaw(
            'tbl_city.id_city, 
            tbl_city.id_state,
            tbl_state.state_name,
            tbl_state.state_name_en,
            tbl_state.state_image,
            COUNT(tbl_order_detail.id) AS total'
        )
            ->whereHas('product', function ($q) {
                $q->availableQuota();
            })
            ->join('tbl_product', 'tbl_order_detail.id_product', '=', 'tbl_product.id_product')
            ->join('tbl_company', 'tbl_company.id_company', '=', 'tbl_product.id_company')
            ->join('tbl_city', 'tbl_city.id_city', '=', 'tbl_order_detail.id_city')
            ->join('tbl_state', 'tbl_city.id_state', '=', 'tbl_state.id_state')
            ->where('tbl_order_detail.created_at', '>', '2019-03-01 00:00:00')
            ->where('tbl_product.deleted_at', null)
            ->groupBy('tbl_city.id_state')
            ->where('tbl_product.publish', 1)
            ->where('tbl_product.status', 1);

        $data['is_home'] = true;
        $data['top_products'] = $data['top_products']
            ->groupBy('tbl_order_detail.id_city')->orderBy('total', 'desc')->take(8)->get();
        $data['recommended_products'] = $data['recommended_products']->take(8)->get();

        $data['top_cities'] = $data['top_cities']
            ->groupBy('tbl_order_detail.id_city')->orderBy('total', 'desc')->take(6)->get();
        $data['top_states'] = $data['top_states']->orderBy('total', 'desc')->take(6)->get();


        if ($request->get('klhk')):
//            $data['top_products'] = $data['top_products']->where('tbl_company.is_klhk', 1);
            $data['top_products'] = Product::availableQuota()->where([
                'publish' => 1,
                'status' => 1
            ])->whereHas('company', function ($company) {
                $company->where('status', 1)->where('is_klhk', 1);
            })->inRandomOrder()->take(8)->get();

            $data['recommended_products'] = Product::availableQuota()->where([
                'publish' => 1,
                'status' => 1
            ])->whereHas('company', function ($company) {
                $company->where('status', 1)->where('is_klhk', 1);
            })->latest()->take(8)->get();
//            $data['top_states'] = State::whereHas('city', function ($city){
//                $city->whereHas('products', function ($product){
//                    $product->where([
//                        'publish'=>1,
//                        'status'=>1
//                    ])->availableQuota()->whereHas('company', function ($company){
//                        $company->where('status',1)->where('is_klhk', 1);
//                    });
//                });
//            })->take(8)->get();
            $data['top_states'] = \App\Models\State::whereHas('country', function ($c) {
                $c->where('sortname', 'ID');
            })->withCount(['products' => function ($product) {
                $product->availableQuota()->whereHas('company', function ($c) {
                    $c->where('is_klhk', 1);
                })->where('status', 1)->where('publish', 1);
            }])->whereNotNull('state_image')->orderBy('products_count', 'desc')->take(6)->get();
        endif;


        $data['seo'] = SeoPage::where('section_slug', 'homepage')->whereHas('category', function ($category) {
            $category->where('category_page_slug', 'b2c-marketplace');
        })->first();
        if ($request->get('klhk') == true):
            return view('klhk.explore.page.homepage.homepage', $data);
        endif;

        return view('explore.page.homepage.homepage', $data);

    }

    public function partnerWithUs()
    {
        return view('landing.index');
    }

    public function faqLanding()
    {
        return view('landing.faq');
    }

    public function heirProvisions()
    {
        return view('landing.heir_provision');
    }

    public function faqWidget()
    {
        return view('landing.widget_faq');
    }

    /**
     * show register form
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewRegister(Request $request)
    {
        $data['email'] = $request->get('email');
        $data['local'] = app()->getLocale();
        return view('auth.new-login.register', $data);
    }
function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}

    /**
     * render Product partially
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function getRenderProducts(Request $request)
    {
		DB::enableQueryLog();// untuk enable debug query	
        if ($request->isXmlHttpRequest()) {
            $company = Company::where('id_company', $request->get('my_company'))->first();
            if ($company) {
              
                $top_products = [];
                $p = [];

                $total_product = Product::availableQuota()
                    ->where([
                        'publish' => 1,
                        'status' => 1,
                        'booking_type' => 'online',
                        'id_company' => $company->id_company
                    ])
                    ->count();

                if ($total_product > 3) {
                    $top_products = Product::where([
                        'publish' => 1,
                        'status' => 1,
                        'booking_type' => 'online',
                        'id_company' => $company->id_company
                    ])
                        ->availableQuota()
                        ->withCount('order_detail')
                        ->orderBy('order_detail_count', 'desc')
                        ->orderBy('viewed', 'desc')
                        ->limit(3)
                        ->get();

                    $p = $top_products->pluck('id_product')->toArray();
                }
				
				

                $products = Product::where('id_company', $company->id_company)->where('publish',
                   1)->availableQuota();
					
					
				
					
                if($company->show_popular == '1'){
                        $products = $products->whereNotIn('tbl_product.id_product', $p);
                }
				
				

               if ($request->has('keyword') && $request->get('keyword') !== null && $request->get('keyword') !== '') {
                    $products = $products->where('tbl_product.product_name', 'like',
                        '%' . trim($request->get('keyword')) . '%');
                }
				
				if ($request->has('kategori') && $request->get('kategori') !== null && $request->get('kategori') !== '') {
					
					$kat = trim($request->get('kategori'));
					$arrKat = explode(",",$kat);
                    $products =   $products->join('tbl_tag_products_value', 'tbl_tag_products_value.product_id','=','tbl_product.id_product')
										   ->join('tbl_tag_products', 'tbl_tag_products.id','=','tbl_tag_products_value.tag_product_id')
											->whereIn('tbl_tag_products_value.tag_product_id', $arrKat)
											->where('tbl_tag_products.status','1')
											->orderBy('tbl_tag_products.name_ind'); 
						
				
                }
				/*if ($request->has('tag') && $request->get('tag') !== null && $request->get('tag') !== '') {
					
					$tag = trim($request->get('tag'));
					$arrTag = explode(",",$tag);
                  
					$products = $products->join('tbl_tag_products_value','tbl_tag_products_value.product_id','=', 'tbl_product.id_product' )
					->whereIn("tbl_tag_products_value.tag_product_id", $arrTag);
					//DB::getQueryLog();
                }*/
				
				
				
				if ($request->has('prov') && $request->get('prov') !== null && $request->get('prov') !== '') {
					$products = $products->join('tbl_city','tbl_city.id_city','=', 'tbl_product.id_city' )
					->where("tbl_city.id_state", $request->get('prov'));	
					
                }

				
                if ($request->has('city') && $request->get('city') !== null && $request->get('city') !== '') {
                    $products = $products->where('tbl_product.id_city', $request->get('city'));
					//$products->get();
					//return $this->console_log(DB::getQueryLog());
                }
				
                if ($request->has('sort') && $request->get('sort') !== null && $request->get('sort') !== '') {
                    switch ($request->get('sort')) {
                        case 'name_asc':
                            $products->orderBy('tbl_product.product_name', 'ASC');
                            break;
                        case 'name_desc':
                            $products->orderBy('tbl_product.product_name', 'DESC');
                            break;
                        case 'newest':
                            $products->orderBy('tbl_product.created_at', 'DESC');
                            break;
                        case 'oldest':
                            $products->orderBy('tbl_product.created_at', 'ASC');
                            break;
                        case 'cheapest':
                            $products->selectRaw("tbl_product.*, MIN(tbl_product_pricing.price) as min_price")->join('tbl_product_pricing',
                                'tbl_product_pricing.id_product', '=', 'tbl_product.id_product')
                                ->groupBy('tbl_product_pricing.id_product')->orderBy('min_price', 'ASC');
                            break;
                        case 'most_expensive':
                            $products->selectRaw("tbl_product.*, MIN(tbl_product_pricing.price) as min_price")->join('tbl_product_pricing',
                                'tbl_product_pricing.id_product', '=', 'tbl_product.id_product')
                                ->groupBy('tbl_product_pricing.id_product')->orderBy('min_price', 'DESC');
                            break;
                    }

                }
				
				
                if ($request->get('klhk')) {
                    return view('klhk.customer.home.partial-products', ['products' => $products->paginate(6)]);
                }
				
                return view('customer.home.partial-products', ['products' => $products->paginate(6)]);
            }
            return response()->json('company salah');
        }
        return response()->json('udu ajax');
    }
	
	 public function ambilKota(Request $request)
    {
		$this->initialize($request);
		$id_state =   $request->input('id_state');
		
				
              

                $products = [];
                $p = [];
				
				
                $total_product = Product::availableQuota()
                    ->where([
                        'publish' => 1,
                        'status' => 1,
                        'booking_type' => 'online',
                        'id_company' => $this->company
                    ])
                    ->count();

                if ($total_product > 3) {
                    $products = Product::where([
                        'publish' => 1,
                        'status' => 1,
                        'booking_type' => 'online',
                        'id_company' => $this->company
                    ])
                        ->availableQuota()
                        ->withCount('order_detail')
                        ->orderBy('order_detail_count', 'desc')
                        ->orderBy('viewed', 'desc')
                        ->limit(3)
                        ->get();

                    $p = $products->pluck('id_product')->toArray();
                }
	
		
		$company = $this->company;
		$kota = City::whereHas('product', function ($product) use($company, $p) {
                            $product->where('id_company', $company)->where('publish', 1)->where('status', 1)->where('booking_type', 'online')->where('deleted_at', null)->whereNotIn('id_product', $p)->availableQuota();
                        })
						->select('id_city', 'city_name')
						->where('id_state', $id_state)
                        ->get();
                        
		//return DB::getQueryLog();
		//$this->console_log(DB::getQueryLog());
		//$this->console_log($kota);
		//return $kota;
		$sKota = '';
		foreach($kota as $k){
			$sKota.="<option value='".$k->id_city."' >$k->city_name</option>";
		}
		
		return $sKota;
		
	}
	
	
}
