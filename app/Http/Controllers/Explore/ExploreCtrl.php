<?php

namespace App\Http\Controllers\Explore;

use App\Models\City;
use App\Models\Language;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\SeoPage;
use App\Models\State;
use App\Models\TagProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExploreCtrl extends Controller
{

    public function afterSearch($type, Request $request)
    {
        $data['type'] = $type;
        $data['is_transport'] = false;
        $data['is_city'] = false;
        if (checkRequestExists($request, 'q', 'GET')) {
            $data['tags'] = TagProduct::with(['products' => function ($p) use ($request) {
                $p->where('deleted_at', null)->where('publish', 1)->where('status', 1)->whereHas('company', function ($c) use ($request) {
                    $c->where('status', 1)->whereNotNull('domain_memoria');
                    if ($request->get('klhk')){
                        $c->klhk();
                    }
                })->availableQuota()->where('tbl_product.product_name', 'like', '%' . $request->get('q') . '%');
            }])->whereHas('products', function ($product) use ($request) {
                $product
                    ->where('deleted_at', null)->where('publish', 1)->where('status', 1)->availableQuota()
                    ->whereHas('company', function ($c) use ($request) {
                        $c->where('status', 1)->whereNotNull('domain_memoria');
                        if ($request->get('klhk')){
                            $c->klhk();
                        }
                    })
                    ->where('tbl_product.product_name', 'like', '%' . $request->get('q') . '%')
                ;
            });
            if (app()->getLocale()=='id'){
                $data['tags']->orderBy('name_ind');
            }else{
                $data['tags']->orderBy('name');
            }
            $data['tags'] = $data['tags']->get();
            $data['nones'] = Product::query()
                ->where('deleted_at', null)->where('publish', 1)->where('status', 1)
                ->whereHas('company', function ($c) use ($request) {
                    $c->where('status', 1)->whereNotNull('domain_memoria');
                    if ($request->get('klhk')){
                        $c->klhk();
                    }
                })->availableQuota()
                ->where('tbl_product.product_name', 'like', '%' . $request->get('q') . '%')->whereDoesntHave('tags')->count();
            $data['languages'] = Language::with(['products' => function ($p) use ($request) {
                $p->where('deleted_at', null)->where('publish', 1)->where('status', 1)->whereHas('company', function ($c) use ($request) {
                    $c->where('status', 1)->whereNotNull('domain_memoria');
                    if ($request->get('klhk')){
                        $c->klhk();
                    }
                })->availableQuota()->where('tbl_product.product_name', 'like', '%' . $request->get('q') . '%');
            }])->whereHas('products', function ($product) use ($request) {
                $product->where('deleted_at', null)->where('publish', 1)->where('status', 1)->whereHas('company', function ($c) use ($request) {
                    $c->where('status', 1)->whereNotNull('domain_memoria');
                    if ($request->get('klhk')){
                        $c->klhk();
                    }
                })->availableQuota()->where('tbl_product.product_name', 'like', '%' . $request->get('q') . '%');
            })->get();
        } else {
            $data['tags'] = TagProduct::with(['products' => function ($p) use ($request) {
                $p->where('deleted_at', null)->where('publish', 1)->where('status', 1)->availableQuota()->whereHas('company', function ($c) use ($request) {
                    $c->where('status', 1)->whereNotNull('domain_memoria');
                    if ($request->get('klhk')){
                        $c->klhk();
                    }
                });
            }])->whereHas('products', function ($product) use ($request) {
                $product->where('deleted_at', null)->where('publish', 1)->where('status', 1)->availableQuota()->whereHas('company', function ($c) use ($request) {
                    $c->where('status', 1)->whereNotNull('domain_memoria');
                    if ($request->get('klhk')){
                        $c->klhk();
                    }
                });
            });
            if (app()->getLocale()=='id'){
                $data['tags']->orderBy('name_ind');
            }else{
                $data['tags']->orderBy('name');
            }
            $data['tags'] = $data['tags']->get();
            $data['nones'] = Product::query()
                ->where('deleted_at', null)->where('publish', 1)->where('status', 1)
                ->whereHas('company', function ($c) use ($request) {
                    $c->where('status', 1)->whereNotNull('domain_memoria');
                    if ($request->get('klhk')){
                        $c->klhk();
                    }
                })
                ->whereDoesntHave('tags')->availableQuota()->count();
            $data['languages'] = Language::with(['products' => function ($p) use ($request) {
                $p->where('deleted_at', null)->where('publish', 1)->where('status', 1)->availableQuota()->whereHas('company', function ($c) use ($request) {
                    $c->where('status', 1)->whereNotNull('domain_memoria');
                    if ($request->get('klhk')){
                        $c->klhk();
                    }
                });
            }])->whereHas('products', function ($product) use ($request) {
                $product->where('deleted_at', null)->where('publish', 1)->where('status', 1)->availableQuota()->whereHas('company', function ($c) use ($request) {
                    $c->where('status', 1)->whereNotNull('domain_memoria');
                    if ($request->get('klhk')){
                        $c->klhk();
                    }
                });
            })->get();
        }

        switch ($type) {
            case 'all-activities':
                $data['seo'] = SeoPage::where('section_slug', 'explore-activities')->whereHas('category', function ($category) {
                    $category->where('category_page_slug', 'b2c-marketplace');
                })->first();
                $data['title'] = trans('explore-lang.header.explore_act');
                break;
            case 'city':
                $data['seo'] = SeoPage::where('section_slug', 'explore-destination')->whereHas('category', function ($category) {
                    $category->where('category_page_slug', 'b2c-marketplace');
                })->first();
                $data['is_city'] = true;
                $city = State::find($request->get('city'));
                if (checkRequestExists($request, 'city', 'GET')) {
                    $data['tags'] = TagProduct::with(['products' => function ($p) use ($request) {
                        $p->where('deleted_at', null)->where('publish', 1)->where('status', 1)->availableQuota()->whereHas('company', function ($c) use ($request) {
                            $c->where('status', 1)->whereNotNull('domain_memoria');
                            if ($request->get('klhk')){
                                $c->klhk();
                            }
                        })->whereHas('city', function ($c) use ($request) {
                            $c->where('id_state', $request->get('city'));
                        });
                    }])->whereHas('products', function ($product) use ($request) {
                        $product->where('deleted_at', null)->where('publish', 1)->where('status', 1)->availableQuota()->whereHas('company', function ($c) use ($request) {
                            $c->where('status', 1)->whereNotNull('domain_memoria');
                            if ($request->get('klhk')){
                                $c->klhk();
                            }
                        })->whereHas('city', function ($c) use ($request) {
                            $c->where('id_state', $request->get('city'));
                        });
                    })->get();
                    $data['nones'] = Product::query()
                        ->where('deleted_at', null)->where('publish', 1)->where('status', 1)->whereHas('company', function ($c) use ($request) {
                            $c->where('status', 1)->whereNotNull('domain_memoria');
                            if ($request->get('klhk')){
                                $c->klhk();
                            }
                        })->whereHas('city', function ($c) use ($request) {
                            $c->where('id_state', $request->get('city'));
                        })->availableQuota()->count();
                } else {
                    $data['tags'] = TagProduct::with(['products' => function ($p) use ($request) {
                        $p->where('deleted_at', null)->where('publish', 1)->where('status', 1)->whereHas('company', function ($c) use ($request) {
                            $c->where('status', 1)->whereNotNull('domain_memoria');
                            if ($request->get('klhk')){
                                $c->klhk();
                            }
                        })->availableQuota();
                    }])->whereHas('products', function ($product) {
                        $product->where('deleted_at', null)->where('publish', 1)->where('status', 1)->whereHas('company', function ($c) {
                            $c->where('status', 1)->whereNotNull('domain_memoria');
                            if (\request()->get('klhk')){
                                $c->klhk();
                            }
                        })->availableQuota();
                    });
                    if (app()->getLocale()=='id'){
                        $data['tags']->orderBy('name_ind');
                    }else{
                        $data['tags']->orderBy('name');
                    }
                    $data['tags'] = $data['tags']->get();
                    $data['nones'] = Product::query()
                        ->where('deleted_at', null)->where('publish', 1)->where('status', 1)->whereHas('company', function ($c) use ($request) {
                            $c->where('status', 1)->whereNotNull('domain_memoria');
                            if ($request->get('klhk')){
                                $c->klhk();
                            }
                        })->availableQuota()->count();
                }
                $data['languages'] = Language::with(['products' => function ($p) use ($request) {
                    $p->where('deleted_at', null)->where('publish', 1)->where('status', 1)->availableQuota()->whereHas('company', function ($c) use ($request) {
                        $c->where('status', 1)->whereNotNull('domain_memoria');
                        if ($request->get('klhk')){
                            $c->klhk();
                        }
                    })->whereHas('city', function ($c) use ($request) {
                        $c->where('id_state', $request->get('city'));
                    });
                }])->whereHas('products', function ($product) use ($request) {
                    $product->where('deleted_at', null)->where('publish', 1)->where('status', 1)->availableQuota()->whereHas('company', function ($c) use ($request) {
                        $c->where('status', 1)->whereNotNull('domain_memoria');
                        if ($request->get('klhk')){
                            $c->klhk();
                        }
                    })->whereHas('city', function ($c) use ($request) {
                        $c->where('id_state', $request->get('city'));
                    });
                })->get();
                if ($city) {
                    if (app()->getLocale() == 'id') {
                        $data['title'] = trans('explore-lang.header.explore_act') . ' di ' . $city->state_name;
                    } else {
                        $data['title'] = ucwords(strtolower($city->state_name)) . ' ' . trans('explore-lang.header.explore_act');
                    }

                } else {
                    $data['title'] = trans('explore-lang.header.explore_act');
                }
                break;

            case 'transports':
                $data['title'] = trans('explore-lang.header.transport');
                $data['is_transport'] = true;
                $data['seo'] = SeoPage::where('section_slug', 'transport')->whereHas('category', function ($category) {
                    $category->where('category_page_slug', 'b2c-marketplace');
                })->first();
                $data['languages'] = Language::whereHas('products', function ($product) use ($request) {
                    $product->where('deleted_at', null)->where('publish', 1)->where('status', 1)->availableQuota()->whereHas('company', function ($c) use ($request) {
                        $c->where('status', 1)->whereNotNull('domain_memoria');
                        if ($request->get('klhk')==1){
                            $c->klhk();
                        }
                    })->whereHas('tags', function ($lang) use ($request) {
                        $lang->where('name', 'like', '%car%')
                            ->orWhere('name', 'like', '%rent%')
                            ->orWhere('name', 'like', '%motor%')
                            ->orWhere('name', 'like', 'truck%')
                            ->orWhere('name', 'like', 'ship%')
                            ->orWhere('name', 'like', '%bus%')
                            ->orWhere('name', 'like', '%taxi%')
                            ->orWhere('name', 'like', '%boat%')
                            ->orWhere('name', 'like', '%yacht%')
                            ->orWhere('name', 'like', '%bicycle%');

                    });
                })->get();
                break;

            default:
                $data['seo'] = SeoPage::where('section_slug', 'explore-activities')->whereHas('category', function ($category) {
                    $category->where('category_page_slug', 'b2c-marketplace');
                })->first();
                $data['title'] = trans('explore-lang.header.explore_act');

        }
        if ($request->get('klhk')){
            return view('klhk.explore.page.result.result', $data);
        }
        return view('explore.page.result.result', $data);

    }

    public function allDestination()
    {
        $data['seo'] = SeoPage::where('section_slug', 'explore-destination')->whereHas('category', function ($category) {
            $category->where('category_page_slug', 'b2c-marketplace');
        })->first();
        if (\request()->get('klhk')){
            return view('klhk.explore.page.destination.all', $data);
        }
        return view('explore.page.destination.all', $data);
    }

    public function renderCity()
    {
        $data['cities'] = State::whereHas('city', function ($city) {
            $city->whereHas('product', function ($p) {
                $p->where('publish', 1)->where('status', 1)->availableQuota();
                if (\request()->get('klhk')){
                    $p->whereHas('company', function ($c){
                       $c->klhk();
                    });
                }
            });
        })->paginate(12);
        if (\request()->get('klhk')){
            return view('klhk.explore.render.city', $data);
        }
        return view('explore.render.city', $data);
    }

    public function searchAjax(Request $request)
    {
        $keyword = $request->input('q');
        $product = Product::has('fix_company')->where('publish', 1)->where('status', 1);
        if (\request()->get('klhk')){
            $product = $product->whereHas('company', function ($c){
               $c->klhk();
            });
        }
        if (checkRequestExists($request, 'city', 'GET')) {
            $product = $product->whereHas('city', function ($city) use ($request) {
                $city->where('id_state', $request->get('city'));
            });
        }
        if (checkRequestExists($request, 'guides')) {
            if (count($request->get('guides')) > 0) {
                $product = $product->whereHas('languages', function ($lang) use ($request) {
                    $lang->whereIn('tbl_language.id_language', $request->get('guides'));
                });
            }
        }
        if (checkRequestExists($request, 'transport')) {
            $product = $product->whereHas('tags', function ($lang) use ($request) {
                $lang->where('name', 'like', '%car%')
                    ->orWhere('name', 'like', '%rent%')
                    ->orWhere('name', 'like', '%motor%')
                    ->orWhere('name', 'like', 'truck%')
                    ->orWhere('name', 'like', 'ship%')
                    ->orWhere('name', 'like', '%bus%')
                    ->orWhere('name', 'like', '%taxi%')
                    ->orWhere('name', 'like', '%boat%')
                    ->orWhere('name', 'like', '%yacht%')
                    ->orWhere('name', 'like', '%bicycle%');

            });
        } elseif (checkRequestExists($request, 'categories')) {

            if (count($request->get('categories')) > 0) {
                if (in_array(null,$request->get('categories')) && count($request->get('categories')) > 1){
                    $product = $product->where(function ($where) use ($request){
                        $where->whereHas('tags', function ($lang) use ($request) {
                            $lang->whereIn('tbl_tag_products.id', array_filter($request->get('categories')));
                        })->orWhereDoesntHave('tags');
                    });

                }elseif(!in_array(null,$request->get('categories'))){
                    $product = $product->whereHas('tags', function ($lang) use ($request) {
                        $lang->whereIn('tbl_tag_products.id', $request->get('categories'));
                    });
                }else{
                    $product = $product->whereDoesntHave('tags');
                }

            }
        }
        if (checkRequestExists($request, 'price')) {
            $ex = explode('-', $request->get('price'));
            if (count($ex) == 2) {
                $start = (double)trim($ex[0]);
                $end = (double)trim($ex[1]);
                if (($start != 0 && $end != 0) && ($start <= $end)) {
                    $product = $product->whereHas('pricing', function ($price) use ($start, $end) {
                        $price->where('price', '>=', $start)->where('price', '<=', $end);
                    });
                }
            }
        }

//        $product->orderBy('created_at', 'desc');

        $data['products'] = $product->availableQuota()->where('tbl_product.product_name','like','%'.$keyword.'%')->orderBy('tbl_product.created_at','desc')->with
        ('company')->paginate
        (30)->appends($request->all());
        $data['referer'] = $request->header('referer');
        if (\request()->get('klhk')){
            return view('klhk.explore.render.products', $data);
        }
        return view('explore.render.products', $data);
    }

    /**
     *get data for popular city
     */
    public function popularCity()
    {
        $data['top_product'] = OrderDetail::selectRaw('tbl_city.id_city,tbl_city.city_name,COUNT(tbl_order_detail.id_product) AS total')
            ->whereHas('product', function ($q){
                $q->availableQuota();
            })
            ->join('tbl_product', 'tbl_order_detail.id_product', '=', 'tbl_product.id_product')
            ->join('tbl_company', 'tbl_company.id_company', '=', 'tbl_product.id_company')
            ->join('tbl_city', 'tbl_city.id_city', '=', 'tbl_order_detail.id_city')
            ->where('tbl_order_detail.created_at', '>', '2019-03-01 00:00:00');

        if (\request()->get('klhk')){
            $data['top_product'] = $data['top_product']->where('tbl_company.is_klhk',1);
        }
        $data['top_product'] = $data['top_product'] ->groupBy('tbl_order_detail.id_city')->orderBy('total', 'desc')->take(5)->get();
        skema($data['top_product']->toArray());
    }

    public function help()
    {
        $data['seo'] = SeoPage::where('section_slug', 'help')->whereHas('category', function ($category) {
            $category->where('category_page_slug', 'b2c-marketplace');
        })->first();
        if (\request()->get('klhk')) {
            return view('klhk.explore.page.help.help', $data);
        }
        return view('explore.page.help.help', $data);
    }

    public function policy()
    {
        $data['seo'] = SeoPage::where('section_slug', 'privacy-policy')->whereHas('category', function ($category) {
            $category->where('category_page_slug', 'b2c-marketplace');
        })->first();
        if (\request()->get('klhk')) {
            return view('klhk.explore.page.policy.policy', $data);
        }
        return view('explore.page.policy.policy', $data);
    }

    public function termCondition()
    {
        $data['seo'] = SeoPage::where('section_slug', 'term-condition')->whereHas('category', function ($category) {
            $category->where('category_page_slug', 'b2c-marketplace');
        })->first();
        if (\request()->get('klhk')) {
            return view('klhk.explore.page.tos.index', $data);
        }
        return view('explore.page.tos.index', $data);
    }

    public function careers()
    {
        $data['seo'] = SeoPage::where('section_slug', 'career')->whereHas('category', function ($category) {
            $category->where('category_page_slug', 'b2c-marketplace');
        })->first();
        if (\request()->get('klhk')) {
            return view('klhk.explore.page.careers.careers', $data);
        }
        return view('explore.page.careers.careers', $data);
    }

    public function about_us()
    {
        $data['seo'] = SeoPage::where('section_slug', 'about-us')->whereHas('category', function ($category) {
            $category->where('category_page_slug', 'b2c-marketplace');
        })->first();
        if (\request()->get('klhk')) {
            return view('klhk.explore.page.about_us.about_us', $data);
        }
        return view('explore.page.about_us.about_us', $data);
    }
}
