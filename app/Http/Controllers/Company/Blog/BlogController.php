<?php

namespace App\Http\Controllers\Company\Blog;

use App\Models\BlogPost;
use App\Models\CategoryBlog;
use App\Scopes\AudienceProviderGeneralScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isXmlHttpRequest()){
            if ($request->has('slug') && $request->get('slug')!=''){
                return $this->detail($request);
            }
        }
        $data['blogCategories'] = CategoryBlog::withGlobalScope('provider',new AudienceProviderGeneralScope())->withCount('publishedPost')->get();
        $data['recent_blogs'] = BlogPost::withGlobalScope('provider',new AudienceProviderGeneralScope())->blog()->publish()->latest()->take(3)->get();
        $data['popular_blogs'] = BlogPost::withGlobalScope('provider',new AudienceProviderGeneralScope())->blog()->publish()->orderBy('viewed','desc')->take(3)->get();
//        $data['blogs'] = BlogPost::withGlobalScope('provider',new AudienceProviderGeneralScope())->blog()->publish()->latest()->paginate(12)->appends($request->all());
        return view('dashboard.company.blog.index',$data);
    }

    public function detail(Request $request)
    {
        $blog = BlogPost::withGlobalScope('provider',new AudienceProviderGeneralScope())->blog()->publish()->where('slug',$request->get('slug'))->first();
        if (!$blog){
            msg(trans('general.not_found_with_name',['name'=>trans('article.article')]),2);
        }
        $blog->increment('viewed');
        $data['blog'] = $blog;
        if ($request->wantsJson()){
            return apiResponse(200,'OK',$data);
        }
        if ($request->isXmlHttpRequest()){
            return view('dashboard.company.blog.render.detail', $data);
        }

        return view('dashboard.company.blog.detail',$data);
    }

    public function searchBlog(Request $request)
    {
        $blog = BlogPost::withGlobalScope('provider',new AudienceProviderGeneralScope())->blog()->publish();
        if (checkRequestExists($request,'q','GET')){
            $blog = $blog->where(function ($where) use ($request){
                $where->where('title_ind',
                    'like', '%'.$request->get('q').'%')
                    ->orWhere('title_eng', 'like', '%'.$request->get('q').'%')
                    ->orWhere('description_ind', 'like', '%'.$request->get('q').'%')
                    ->orWhere('description_eng', 'like', '%'.$request->get('q').'%');
            });
        }

        if (checkRequestExists($request,'category','GET')){
            $blog = $blog->where('category_blog_id',$request->get('category'));
        }
        $data['blogs'] = $blog->latest()->paginate(6)->appends($request->all());
        if ($request->wantsJson()){
            return apiResponse(200,'OK',$data);
        }
        if ($request->isXmlHttpRequest()){
            return view('dashboard.company.blog.render.list', $data);
        }
        return view('dashboard.company.blog.index',$data);
    }

}
