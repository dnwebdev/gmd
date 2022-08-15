<?php

namespace App\Http\Controllers\Backoffice\Company\Blog;

use App\Models\Audience;
use App\Models\CategoryBlog;
use App\Scopes\AudienceCustomerGeneralScope;
use App\Scopes\AudienceProviderGeneralScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class CategoryCtrl extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        toastr();
        $data['url_data'] = route('admin:b2b.category.data');
        $data['url_create'] = route('admin:b2b.category.save');
        $data['url_update'] = route('admin:b2b.category.update');
        $data['url_delete'] = route('admin:b2b.category.delete');
        $data['title'] = 'B2B Blog Category';
        return view('back-office.page.directory.blog.category.index', $data);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function loadData()
    {
        $models = CategoryBlog::withGlobalScope('provider', new AudienceProviderGeneralScope());
        return \DataTables::of($models)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                $html = '<button data-category_name_ind="'.$model->category_name_ind.'" data-category_name_eng="'.$model->category_name_eng.'" data-id="'.$model->id.'"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-visible"></i></button>';
                $html .= ' <button data-category_name_eng="'.$model->category_name_eng.'" data-category_name_ind="'.$model->category_name_ind.'" data-id="'.$model->id.'" class="btn-delete btn btn-outline-danger btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air">';
                $html .= '<i class="fa fa-trash"></i>';
                $html .= '</button>';
                return $html;
            })
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $provider = Audience::firstOrCreate(['audience_name' => 'provider']);
        $rule = [
            'category_name_eng' => [
                'required',
                Rule::unique('category_blogs', 'category_name_eng')->where('audience_id', $provider->id),
            ],

            'category_name_ind' => [
                'required',
                Rule::unique('category_blogs', 'category_name_ind')->where('audience_id', $provider->id),
            ],
        ];
        $this->validate($request, $rule);
        try {
            \DB::beginTransaction();
            $id = null;
            if ($customerGeneralAudience = Audience::audienceType('provider')->first()) {
                $id = $customerGeneralAudience->id;
            }

            $categoryStore = new CategoryBlog;
            $categoryStore->category_name_ind = $request->get('category_name_ind');
            $categoryStore->category_name_eng = $request->get('category_name_eng');
            $categoryStore->audience_id = $id;
            $categoryStore->save();
            msg('Blog Category created');
            \DB::commit();
            return redirect()->back();
        } catch (\Exception $exception) {
            \DB::rollBack();
            msg('Something Wrong');
            return redirect()->back();
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int                       $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $provider = Audience::firstOrCreate(['audience_name' => 'provider']);
        $rule = [
            'id' => 'required|exists:category_blogs,id',
            'category_name_eng' => [
                'required',
                Rule::unique('category_blogs', 'category_name_eng')->whereNot('id', $request->id)->where('audience_id',$provider->id)
            ],
            'category_name_ind' => [
                'required',
                Rule::unique('category_blogs', 'category_name_ind')->whereNot('id', $request->id)->where('audience_id',$provider->id)
            ]
        ];
        $this->validate($request, $rule);
        $categoryUpdate = CategoryBlog::withGlobalScope('provider', new AudienceProviderGeneralScope())->where('id',
            $request->id)->first();
        if ($categoryUpdate) {
            $categoryUpdate->update([
                'category_name_eng' => $request->input('category_name_eng'),
                'category_name_ind' => $request->input('category_name_ind')
            ]);
            msg('Category Blog Updated!');
            return redirect()->back();
        }
        msg('Category Blog not found!', 2);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $categoryDelete = CategoryBlog::withGlobalScope('provider', new AudienceProviderGeneralScope())->where('id',
            $request->id)->first();
        if ($categoryDelete) {
            $categoryDelete->delete();
            msg('Category Blog Deleted!');
            return redirect()->back();
        }
        msg('Category Blog not found!', 2);
        return redirect()->back();
    }
}
