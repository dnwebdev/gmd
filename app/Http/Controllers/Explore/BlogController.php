<?php

namespace App\Http\Controllers\Explore;

use App\Models\BlogPost;
use App\Models\CategoryBlog;
use App\Models\SeoPage;
use App\Scopes\AudienceCustomerGeneralScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        toastr();
        $blogs = BlogPost::withGlobalScope('customer',
            new AudienceCustomerGeneralScope())->publish()->blog()->latest()->paginate(5);
        $popularBlog = BlogPost::withGlobalScope('customer',
            new AudienceCustomerGeneralScope())->publish()->blog()->orderBy('viewed',
            'desc')->take(3)->get();
        $recentBlog = BlogPost::withGlobalScope('customer',
            new AudienceCustomerGeneralScope())->publish()->blog()->orderBy('created_at',
            'desc')->take(3)->get();
        $seo = SeoPage::where('section_slug', 'blog-post')->whereHas('category', function ($category) {
            $category->where('category_page_slug', 'b2c-marketplace');
        })->first();
        return view('explore.page.blog.blog', compact('blogs', 'popularBlog', 'recentBlog', 'seo'));
    }

    /**
     * @param           $slug
     * @param  Request  $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function detailBlog($slug, Request $request)
    {
        $blog = BlogPost::withGlobalScope('customer',
            new AudienceCustomerGeneralScope())->publish()->blog()->where('slug', $slug)->first();
        $popularBlog = BlogPost::withGlobalScope('customer',
            new AudienceCustomerGeneralScope())->publish()->blog()->orderBy('viewed',
            'desc')->take(3)->get();
        $recentBlog = BlogPost::withGlobalScope('customer',
            new AudienceCustomerGeneralScope())->publish()->blog()->orderBy('created_at',
            'desc')->take(3)->get();
        if (!$blog) {
            msg('Blog Not Found', 2);
            return redirect()->route('explore.blog.index');
        }
        $countBlog = BlogPost::withGlobalScope('customer',
            new AudienceCustomerGeneralScope())->publish()->blog()->where('admin_id',
            $blog->author->id)->count();
        $blog->increment('viewed');
        $next = BlogPost::where('page', '>', $blog->page)->withGlobalScope('customer',
            new AudienceCustomerGeneralScope())->publish()->blog()->orderBy('page', 'asc')->first();
        $previous = BlogPost::where('page', '<', $blog->page)->withGlobalScope('customer',
            new AudienceCustomerGeneralScope())->publish()->blog()->orderBy('page', 'desc')->first();
        return view('explore.page.blog.detail_blog',
            compact('blog', 'popularBlog', 'recentBlog', 'countBlog', 'next', 'previous'));
    }

    /**
     * @param           $type
     * @param  Request  $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function afterSearch($type, Request $request)
    {
        $data['type'] = $type;
        $data['category'] = null;
        $data['blogs'] = null;
        if (checkRequestExists($request, 'q', 'GET')) {
            $data['category'] = CategoryBlog::where('category_name_ind', 'like',
                '%'.$request->get('q').'%')->orWhere('category_name_eng', 'like', '%'.$request->get('q').'%')->first();

            if ($data['category']['id']) {
                $data['blogs'] = BlogPost::where('category_blog_id', $data['category']['id'])->withGlobalScope('customer',
                    new AudienceCustomerGeneralScope())->publish()->blog()->orderBy('created_at', 'desc')->get();
            } else {
                $data['blogs'] = BlogPost::withGlobalScope('customer',
                    new AudienceCustomerGeneralScope())->publish()->blog()
                    ->where(function ($where) use ($request){
                        $where->where('title_ind',
                            'like', '%'.$request->get('q').'%')
                            ->orWhere('title_eng', 'like', '%'.$request->get('q').'%')
                            ->orWhere('description_ind', 'like', '%'.$request->get('q').'%')
                            ->orWhere('description_eng', 'like', '%'.$request->get('q').'%');
                    })->get();
            }

        } else {
            $data['blogs'] = BlogPost::withGlobalScope('customer',
                new AudienceCustomerGeneralScope())->publish()->blog()->orderBy('created_at',
                'desc')->paginate(5);
        }
        $data['popularBlog'] = BlogPost::withGlobalScope('customer',
            new AudienceCustomerGeneralScope())->publish()->blog()->orderBy('viewed',
            'desc')->take(3)->get();
        $data['recentBlog'] = BlogPost::withGlobalScope('customer',
            new AudienceCustomerGeneralScope())->publish()->blog()->orderBy('created_at',
            'desc')->take(3)->get();

        return view('explore.page.blog.blog', $data);
    }

    /**
     * @param  Request  $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchAjax(Request $request)
    {
        $keyword = $request->get('q');

        if (checkRequestExists($request, 'q', 'GET')) {
            $category = CategoryBlog::where('category_name_ind', 'like',
                '%'.$request->get('q').'%')->orWhere('category_name_eng', 'like', '%'.$request->get('q').'%')->first();
            if ($category) {
                $blog = BlogPost::where('category_blog_id', $category->id)->withGlobalScope('customer',
                    new AudienceCustomerGeneralScope())->publish()->blog();

            } else {
                $blog = BlogPost::withGlobalScope('customer',
                    new AudienceCustomerGeneralScope())->publish()->blog()
                    ->where(function ($where) use ($request){
                        $where ->where('title_ind', 'like',
                            '%'.$request->get('q').'%')
                            ->orWhere('title_eng', 'like', '%'.$request->get('q').'%')
                            ->orWhere('description_ind', 'like', '%'.$request->get('q').'%')
                            ->orWhere('description_eng', 'like', '%'.$request->get('q').'%');
                    });

            }

        } else {
            $blog = BlogPost::withGlobalScope('customer',
                new AudienceCustomerGeneralScope())->publish()->blog();
        }

        $blog->orderBy('created_at', 'desc');

        $data['blogs'] = $blog->with('categoryBlog')->paginate(5)->appends($request->all());

        return view('explore.render.blog-render', $data);
    }


}
