<?php

namespace App\Http\Controllers\Explore;

use App\Models\BlogPost;
use App\Models\CategoryBlog;
use App\Models\SeoPage;
use App\Scopes\AudienceCustomerGeneralScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PressReleasesCtrl extends Controller
{
    /**
     * @param  Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $seo = SeoPage::where('section_slug', 'press-release')->whereHas('category', function ($category) {
            $category->where('category_page_slug', 'b2c-marketplace');
        })->first();
        $press = BlogPost::withGlobalScope('customer',
            new AudienceCustomerGeneralScope())->publish()->pressRelease()->orderBy('created_at',
            'desc')->get();
        $popularBlog = BlogPost::withGlobalScope('customer',
            new AudienceCustomerGeneralScope())->publish()->pressRelease()->orderBy('viewed',
            'desc')->take(3)->get();
        $recentBlog = BlogPost::withGlobalScope('customer',
            new AudienceCustomerGeneralScope())->publish()->pressRelease()->orderBy('created_at',
            'desc')->take(3)->get();
//        dd($popularBlog);
        return view('explore.page.press_releases.press_releases', compact('press', 'popularBlog', 'recentBlog', 'seo'));
    }

    /**
     * @param $slug
     * @param  Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function detailPres($slug, Request $request)
    {
        $blog = BlogPost::where('type_post', 'press-releases')->where('slug', $slug)->first();
        $popularBlog = BlogPost::withGlobalScope('customer',
            new AudienceCustomerGeneralScope())->publish()->pressRelease()->orderBy('viewed',
            'desc')->take(3)->get();
        $recentBlog = BlogPost::withGlobalScope('customer',
            new AudienceCustomerGeneralScope())->publish()->pressRelease()->orderBy('created_at',
            'desc')->take(3)->get();


        if (!$blog) {
            msg(trans('notification.product.not_found'), 2);
            return redirect()->route('pres_releases.index');
        }
        $countBlog = BlogPost::withGlobalScope('customer',
            new AudienceCustomerGeneralScope())->publish()->pressRelease()->where('admin_id', $blog->author->id)->count();
        $blog->increment('viewed');
        $next = BlogPost::where('page', '>', $blog->page)->withGlobalScope('customer',
            new AudienceCustomerGeneralScope())->publish()->pressRelease()->orderBy('page',
            'asc')->first();
        $previous = BlogPost::where('page', '<', $blog->page)->withGlobalScope('customer',
            new AudienceCustomerGeneralScope())->publish()->pressRelease()->orderBy('page',
            'desc')->first();
        return view('explore.page.press_releases.detail_press',
            compact('blog', 'popularBlog', 'recentBlog', 'countBlog', 'next', 'previous'));
    }

    /**
     * @param $type
     * @param  Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function afterSearch($type, Request $request)
    {
        $data['type'] = $type;
        $data['category'] = null;
        $data['press'] = null;
        if (checkRequestExists($request, 'q', 'GET')) {
            $data['category'] = CategoryBlog::where('category_name_ind', 'like',
                '%'.$request->get('q').'%')->orWhere('category_name_eng', 'like', '%'.$request->get('q').'%')->first();

            if ($data['category']['id']) {
                $data['press'] = BlogPost::withGlobalScope('customer',
                    new AudienceCustomerGeneralScope())->publish()->pressRelease()->where('category_blog_id',
                    $data['category']['id'])->orderBy('created_at', 'desc')->get();
            } else {
                $data['press'] = BlogPost::withGlobalScope('customer',
                    new AudienceCustomerGeneralScope())->publish()->pressRelease()
                    ->where(function ($where) use ($request){
                        $where->where('title_ind', 'like',
                            '%'.$request->get('q').'%')
                            ->orWhere('title_eng', 'like', '%'.$request->get('q').'%')
                            ->orWhere('description_ind', 'like', '%'.$request->get('q').'%');
                    })
                    ->get();
            }

        } else {
            $data['press'] = BlogPost::withGlobalScope('customer',
                new AudienceCustomerGeneralScope())->publish()->pressRelease()->orderBy('created_at', 'desc')->paginate(5);
        }


        $data['popularBlog'] = BlogPost::withGlobalScope('customer',
            new AudienceCustomerGeneralScope())->publish()->pressRelease()->orderBy('viewed', 'desc')->take(3)->get();
        $data['recentBlog'] = BlogPost::withGlobalScope('customer',
            new AudienceCustomerGeneralScope())->publish()->pressRelease()->orderBy('created_at', 'desc')->take(3)->get();

        return view('explore.page.press_releases.press_releases', $data);
    }


    public function searchAjax(Request $request)
    {
        $keyword = $request->get('q');

        if (checkRequestExists($request, 'q', 'GET')) {
            $category = CategoryBlog::where('category_name_ind', 'like',
                '%'.$request->get('q').'%')->orWhere('category_name_eng', 'like', '%'.$request->get('q').'%')->first();
            if ($category) {
                $blog = BlogPost::where('category_blog_id', $category->id)->withGlobalScope('customer',
                    new AudienceCustomerGeneralScope())->publish()->pressRelease();
            } else {
                $blog = BlogPost::withGlobalScope('customer',
                    new AudienceCustomerGeneralScope())->publish()->pressRelease()
                    ->where(function ($where) use ($request){
                        $where->where('title_ind',
                            'like', '%'.$request->get('q').'%')
                            ->orWhere('title_eng', 'like', '%'.$request->get('q').'%')
                            ->orWhere('description_ind', 'like', '%'.$request->get('q').'%')
                            ->orWhere('description_eng', 'like', '%'.$request->get('q').'%');
                    });
            }

        } else {
            $blog = BlogPost::withGlobalScope('customer',
                new AudienceCustomerGeneralScope())->publish()->pressRelease();
        }

        $blog->orderBy('created_at', 'desc');

        $data['press'] = $blog->with('categoryBlog')->paginate(5)->appends($request->all());

        return view('explore.render.press-render', $data);
    }

}
