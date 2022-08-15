<?php

namespace App\Http\Controllers\Backoffice\Company\Blog;

use App\Models\Audience;
use App\Scopes\AudienceProviderGeneralScope;
use File;
use Illuminate\Validation\Rule;
use Image;
use Storage;
use App\Models\BlogPost;
use App\Models\CategoryBlog;
use App\Models\TagBlog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostCtrl extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        toastr();
        $data['url_data'] = route('admin:b2b.post.data');
        $data['url_create'] = route('admin:b2b.post.create');
//        $data['url_update'] = route('admin:b2b.post.update');
        $data['url_delete'] = route('admin:b2b.post.delete');
        $data['url_activated'] = route('admin:b2b.post.active');
        $data['url_deactivated'] = route('admin:b2b.post.nonactive');
        $data['title'] = 'B2B Blog Post';
        return view('back-office.page.directory.blog.post.index', $data);
    }

    public function loadData()
    {
        $models = BlogPost::withGlobalScope('provider', new AudienceProviderGeneralScope())->where('type_post',
            'blog')->orderBy('created_at', 'desc');
        return \DataTables::of($models)
            ->addIndexColumn()
            ->addColumn('created', function ($model) {
                return $model->created_at->format('d M Y H:s');
            })
            ->addColumn('updated', function ($model) {
                return $model->updated_at->format('d M Y H:s');
            })
//            ->addColumn('is_published', function ($model) {
//                if ($model->is_published) {
//                    return $model->is_published;
//                }
//                return '-';
//            })
            ->addColumn('action', function ($model) {
                $html = '<a href="'.route('admin:b2b.post.edit',
                        ['id' => $model->id]).'"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-edit"></i></a>';
                $html .= ' <button data-name="'.$model->title_ind.'" data-id="'.$model->id.'" class="btn-delete btn btn-outline-danger  btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air">';
                $html .= '<i class="fa fa-trash"></i>';
                $html .= '</button>';

                if ($model->is_published == 1) {
                    $html .= ' <button data-id="'.$model->id.'" class="btn-nonactive btn btn-outline-success  btn-sm m-btn m-btn--icon  m-btn--custom m-btn--pill m-btn--air">';
                    $html .= '<i class="fa fa-chevron-circle-up"></i> Active';
                    $html .= '</button>';
                } else {
                    $html .= ' <button data-id="'.$model->id.'" class="btn-active btn btn-outline-danger  btn-sm m-btn m-btn--icon  m-btn--custom m-btn--pill m-btn--air">';
                    $html .= '<i class="fa fa-chevron-circle-down"></i> Non Active';
                    $html .= '</button>';
                }
                return $html;
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['categorys'] = CategoryBlog::withGlobalScope('provider', new AudienceProviderGeneralScope())->get();
        $data['tags'] = TagBlog::withGlobalScope('provider', new AudienceProviderGeneralScope())->get();
        $data['url_save'] = route('admin:b2b.post.save');
        $data['title'] = 'New B2B Blog Post';
        return view('back-office.page.directory.blog.post.add', $data);
    }


    /**
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        $provider = Audience::firstOrCreate(['audience_name' => 'provider']);

        $rule = [
            'title_ind' => [
                'required',
                'max:150',
                Rule::unique('blogposts', 'title_ind')->where('audience_id', $provider->id),
            ],
            'title_eng' => [
                'required',
                'max:150',
                Rule::unique('blogposts', 'title_eng')->where('audience_id', $provider->id),
            ],
            'description_ind' => 'required',
            'description_eng' => 'required',
            'category_blog_id' => 'required',
            'image_blog' => 'required|image',
            'alternative' => 'required|max:255',
            'tag' => 'array|required',
        ];
        $this->validate($request, $rule);

        try {
            \DB::beginTransaction();
            $id = null;
            if ($customerGeneralAudience = Audience::audienceType('provider')->first()) {
                $id = $customerGeneralAudience->id;
            }

            $blog = BlogPost::max('page');
            $post = new BlogPost;
            $post->category_blog_id = $request->category_blog_id;
            $post->title_ind = $request->title_ind;
            $post->title_eng = $request->title_eng;
            $post->description_ind = $request->input('description_ind');
            $post->description_eng = $request->input('description_eng');
            $post->is_published = $request->is_published ? true : false;
            $post->slug = str_slug($request->title_eng, '-');
            $post->admin_id = auth('admin')->user()->id;
            $post->type_post = 'blog';
            $post->alternative = $request->alternative;
            $post->page = $blog + 1;
            $post->audience_id = $id;
//            $post->increment('page', 1);
            $post->save();

//                if ($request->hasFile('image_blog')) {
//                    $file = $request->file('image_blog');
//                    $dataFile = Image::make($file)->fit(870, 342);
//                    $fileName = 'Blog - ' .'(' . $post->created_at->format('d-m-Y H:i:s') . ').'. $dataFile->getClientOriginalExtension();
//                    $post->image_blog = $fileName;
//                    $file->storeAs('public/blog/', $fileName);
//                }

            if ($request->hasFile('image_blog')) {
                $path = 'blog';
                if (!File::isDirectory(Storage::disk('public')->path($path))) {
                    File::makeDirectory(Storage::disk('public')->path($path), 0777, true, true);
                }
                $source = $request->file('image_blog');
                $name = 'blog-'.generateRandomString(6).time().'.'.$source->getClientOriginalExtension();
                if (Image::make($source)->fit(870, 342)->save(Storage::disk('public')->path($path.'/'.$name))) {
                    $post->image_blog = Storage::url($path.'/'.$name);
                }
            }

            $post->save();
            $post->tagPost()->sync($request->tag);

            \DB::commit();
            return apiResponse(200, 'Success add Blog Post', ['redirect' => route('admin:b2b.post.index')]);
        } catch (\Exception $exception) {
            \DB::rollBack();
            return apiResponse(500, __('general.whoops'), getException($exception));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['post'] = BlogPost::withGlobalScope('provider',
            new AudienceProviderGeneralScope())->whereId($id)->first();
        $data['categorys'] = CategoryBlog::withGlobalScope('provider', new AudienceProviderGeneralScope())->get();
        $data['tags'] = TagBlog::withGlobalScope('provider', new AudienceProviderGeneralScope())->get();
        $data['title'] = 'Edit B2B Blog Post';
        return view('back-office.page.directory.blog.post.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int                       $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->ajax() && $request->wantsJson()) {
            $provider = Audience::firstOrCreate(['audience_name' => 'provider']);
            $rule = [
                'description_ind' => 'required',
                'description_eng' => 'required',
                'category_blog_id' => 'required',
                'image_blog' => 'nullable|image',
                'alternative' => 'required|max:255',
                'tag' => 'array|required',
                'title_ind' => [
                    'required',
                    'max:150',
                    Rule::unique('blogposts', 'title_ind')->whereNot('id', $request->id)->where('audience_id',$provider->id)
                ],
                'title_eng' => [
                    'required',
                    'max:150',
                    Rule::unique('blogposts', 'title_eng')->whereNot('id', $request->id)->where('audience_id',$provider->id)
                ],
            ];
            $this->validate($request, $rule);

            try {
                $post = BlogPost::withGlobalScope('provider',
                    new AudienceProviderGeneralScope())->whereId($id)->first();
                if (!$post) {
                    return apiResponse(404, __('general.not_found'));
                }
                \DB::beginTransaction();
                $id = null;
                if ($customerGeneralAudience = Audience::audienceType('provider')->first()) {
                    $id = $customerGeneralAudience->id;
                }
                $post->category_blog_id = $request->category_blog_id;
                $post->title_ind = $request->title_ind;
                $post->title_eng = $request->title_eng;
                $post->audience_id = $id;
                $post->description_ind = $request->input('description_ind');
                $post->description_eng = $request->input('description_eng');
                $post->is_published = $request->is_published ? true : false;
                $post->slug = str_slug($request->title_eng, '-');
                $post->type_post = 'blog';
                $post->alternative = $request->alternative;
                $post->page;
                $post->save();

                /*if ($request->hasFile('image_blog')) {
                    $file = $request->file('image_blog');
                    $fileName = 'Blog - ' .'(' . $post->created_at->format('d-m-Y H:i:s') . ').'. $file->getClientOriginalExtension();
                    $post->image_blog = $fileName;
                    $file->storeAs('public/blog/', $fileName);
                }*/

                if ($request->hasFile('image_blog')) {
                    $path = 'blog';
                    if (!File::isDirectory(Storage::disk('public')->path($path))) {
                        File::makeDirectory(Storage::disk('public')->path($path), 0777, true, true);
                    }
                    $source = $request->file('image_blog');
                    $name = 'blog-'.generateRandomString(6).time().'.'.$source->getClientOriginalExtension();
                    if (Image::make($source)->fit(870, 342)->save(Storage::disk('public')->path($path.'/'.$name))) {
                        $post->image_blog = Storage::url($path.'/'.$name);
                    }
                }
                $post->save();

                $post->tagPost()->sync($request->tag);

                \DB::commit();
                return apiResponse(200, 'Success add Blog Post', ['redirect' => route('admin:b2b.post.index')]);
            } catch (\Exception $exception) {
                \DB::rollBack();
                return apiResponse(500, __('general.whoops'), getException($exception));
            }
        }
        abort(403);
    }

    public function active(Request $request)
    {
        if (!$voucher = BlogPost::withGlobalScope('provider',
            new AudienceProviderGeneralScope())->whereId($request->id)->first()) {
            return apiResponse(404, 'Blog Post not found');
        }
        if ($voucher->is_published != 1) {
            $voucher->update(['is_published' => 1]);
            return apiResponse(200, 'OK');
        }
        return apiResponse(400, 'Already Active');
    }

    public function nonactive(Request $request)
    {
        if (!$voucher = BlogPost::withGlobalScope('provider',
            new AudienceProviderGeneralScope())->whereId($request->id)->first()) {
            return apiResponse(404, 'Voucher Not Found');
        }
        if ($voucher->is_published != 0) {
            $voucher->update(['is_published' => 0]);
            return apiResponse(200, 'OK');
        }
        return apiResponse(400, 'Already Non Active');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $del = BlogPost::withGlobalScope('provider',
            new AudienceProviderGeneralScope())->whereId($request->id)->first();
        if (!$del) {
            msg('Blog Post No Found!', 2);
            return redirect()->back();
        }
        if (!empty($del->image_blog)) {
            Storage::delete('public/uploads/blog/'.$del->image_blog);
        }
        $del->delete();
        msg('Blog Post Deleted!');
        return redirect()->back();

    }
}
