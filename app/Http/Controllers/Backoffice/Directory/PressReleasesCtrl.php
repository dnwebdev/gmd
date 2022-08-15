<?php

namespace App\Http\Controllers\Backoffice\Directory;

use App\Models\Audience;
use App\Models\BlogPost;
use App\Models\CategoryBlog;
use App\Models\TagBlog;
use App\Scopes\AudienceCustomerGeneralScope;
use File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Image;
use Storage;

class PressReleasesCtrl extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        toastr();
        return view('back-office.page.directory.press-releases.index');
    }


    /**
     * @return mixed
     * @throws \Exception
     */
    public function loadData()
    {
        $models = BlogPost::withGlobalScope('customer', new AudienceCustomerGeneralScope())->where('type_post',
            'press-releases')->orderBy('created_at', 'desc`');
        return \DataTables::of($models)
            ->addIndexColumn()
            ->addColumn('created', function ($model) {
                return $model->created_at->format('d M Y H:s');
            })
            ->addColumn('updated', function ($model) {
                return $model->updated_at->format('d M Y H:s');
            })
            ->addColumn('is_published', function ($model) {
                if ($model->is_published) {
                    return $model->is_published;
                }
                return '-';
            })
            ->addColumn('action', function ($model) {
                $html = '<a href="'.route('admin:directory.press-releases.edit',
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
        $categorys = CategoryBlog::withGlobalScope('customer', new AudienceCustomerGeneralScope())->get();
        $tags = TagBlog::withGlobalScope('customer', new AudienceCustomerGeneralScope())->get();
        return view('back-office.page.directory.press-releases.add',
            compact(
                'categorys', 'tags'
            ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        if ($request->ajax() && $request->wantsJson()) {
            $rule = [
                'title_ind' => 'required|unique:blogposts,title_ind',
                'title_eng' => 'required|unique:blogposts,title_eng',
                'description_ind' => 'required',
                'description_eng' => 'required',
                'category_blog_id' => 'required',
                'image_blog' => 'required|image',
                'alternative' => 'required|max:255',
            ];
            $this->validate($request, $rule);

            try {
                \DB::beginTransaction();
                $blog = BlogPost::max('page');
                $id = null;
                if ($customerGeneralAudience = Audience::audienceType('customer')->first()) {
                    $id = $customerGeneralAudience->id;
                }
                $post = new BlogPost;
                $post->category_blog_id = $request->category_blog_id;
                $post->title_ind = $request->title_ind;
                $post->title_eng = $request->title_eng;
                $post->description_ind = $request->description_ind;
                $post->description_eng = $request->description_eng;
                $post->is_published = $request->is_published ? true : false;
                $post->slug = str_slug($request->title_eng, '-');
                $post->admin_id = auth('admin')->user()->id;
                $post->type_post = 'press-releases';
                $post->alternative = $request->alternative;
                $post->page = $blog + 1;
                $post->audience_id = $id;
                $post->save();

//                if ($request->hasFile('image_blog')) {
//                    $file = $request->file('image_blog');
//                    $dataFile = Image::make($file)->fit(870, 342);
//                    $fileName = 'Blog - ' .'(' . $post->created_at->format('d-m-Y H:i:s') . ').'. $dataFile->getClientOriginalExtension();
//                    $post->image_blog = $fileName;
//                    $file->storeAs('public/blog/', $fileName);
//                }

                if ($request->hasFile('image_blog')) {
                    $path = 'press-releases';
                    if (!File::isDirectory(Storage::disk('public')->path($path))) {
                        File::makeDirectory(Storage::disk('public')->path($path), 0777, true, true);
                    }
                    $source = $request->file('image_blog');
                    $name = 'press-releases -'.generateRandomString(6).time().'.'.$source->getClientOriginalExtension();
                    if (Image::make($source)->fit(870, 342)->save(Storage::disk('public')->path($path.'/'.$name))) {
                        $post->image_blog = Storage::url($path.'/'.$name);
                    }
                }

                $post->save();
                $post->tagPost()->sync($request->tag);

                \DB::commit();
                return apiResponse(200, 'Success add Press Releases',
                    ['redirect' => route('admin:directory.press-releases.index')]);
            } catch (\Exception $exception) {
                \DB::rollBack();
                return apiResponse(500, __('general.whoops'), getException($exception));
            }
        }
        abort(403);
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
        $post = BlogPost::withGlobalScope('customer', new AudienceCustomerGeneralScope())->whereId($id)->first();
        $categorys = CategoryBlog::withGlobalScope('customer', new AudienceCustomerGeneralScope())->get();
        $tags = TagBlog::withGlobalScope('customer', new AudienceCustomerGeneralScope())->get();
        return view('back-office.page.directory.press-releases.edit', compact('post', 'categorys', 'tags'));
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
            $rule = [
                'description_ind' => 'required',
                'description_eng' => 'required',
                'category_blog_id' => 'required',
                'image_blog' => 'sometimes|image',
                'alternative' => 'required|max:255',
                'title_ind' => [
                    'required',
                    Rule::unique('blogposts', 'title_ind')->whereNot('id', $request->id)
                ],
                'title_eng' => [
                    'required',
                    Rule::unique('blogposts', 'title_eng')->whereNot('id', $request->id)
                ],
            ];
            $this->validate($request, $rule);

            try {
                \DB::beginTransaction();
                $post = BlogPost::withGlobalScope('customer',
                    new AudienceCustomerGeneralScope())->whereId($id)->first();
                if (!$post) {
                    return apiResponse(404, __('general.not_found'));
                }
                $post->category_blog_id = $request->category_blog_id;
                $post->title_ind = $request->title_ind;
                $post->title_eng = $request->title_eng;
                $post->description_ind = $request->description_ind;
                $post->description_eng = $request->description_eng;
                $post->is_published = $request->is_published ? true : false;
                $post->slug = str_slug($request->title_eng, '-');
                $post->type_post = 'press-releases';
                $post->save();

                /*if ($request->hasFile('image_blog')) {
                    $file = $request->file('image_blog');
                    $fileName = 'Blog - ' .'(' . $post->created_at->format('d-m-Y H:i:s') . ').'. $file->getClientOriginalExtension();
                    $post->image_blog = $fileName;
                    $file->storeAs('public/blog/', $fileName);
                }*/

                if ($request->hasFile('image_blog')) {
                    $path = 'press-releases';
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
                return apiResponse(200, 'Success add Press Releases',
                    ['redirect' => route('admin:directory.press-releases.index')]);
            } catch (\Exception $exception) {
                \DB::rollBack();
                return apiResponse(500, __('general.whoops'), getException($exception));
            }
        }
        abort(403);
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
        $del = BlogPost::withGlobalScope('customer',
            new AudienceCustomerGeneralScope())->whereId($request->id)->first();
        if (!$del){
            msg('Press Releases Not Found!',2);
            return redirect()->back();
        }

        if (!empty($del->image_blog)) {
            Storage::delete('public/uploads/press-releases/'.$del->image_blog);
        }

        $del->delete();

        msg('Press Releases Deleted!');
        return redirect()->back();
    }
}
