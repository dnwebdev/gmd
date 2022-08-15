<?php

namespace App\Http\Controllers\Backoffice\Directory\Blog;

use App\Models\Audience;
use App\Models\TagBlog;
use App\Scopes\AudienceCustomerGeneralScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class TagCtrl extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        toastr();
        $data['title'] = 'B2C tags';
        $data['url_create'] = route('admin:directory.tag.save');
        $data['url_update'] = route('admin:directory.tag.update');
        $data['url_delete'] = route('admin:directory.tag.delete');
        $data['url_data'] = route('admin:directory.tag.data');
        return view('back-office.page.directory.blog.tag.index',$data);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function loadData()
    {
        $models = TagBlog::withGlobalScope('customer',new AudienceCustomerGeneralScope());
        return \DataTables::of($models)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                $html = '<button data-tag_name_ind="' . $model->tag_name_ind . '" data-tag_name_eng="' . $model->tag_name_eng .'" data-id="' . $model->id . '"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-visible"></i></button>';
                $html .= ' <button data-tag_name_eng="' . $model->tag_name_eng . '" data-tag_name_ind="' . $model->tag_name_ind . '" data-id="' . $model->id . '" class="btn-delete btn btn-outline-danger btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air">';
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $provider = Audience::firstOrCreate(['audience_name' => 'customer']);
        $rule = [
            'tag_name_eng' => [
                'required',
                Rule::unique('tag_blogs', 'tag_name_eng')->where('audience_id', $provider->id),
            ],

            'tag_name_ind' => [
                'required',
                Rule::unique('tag_blogs', 'tag_name_ind')->where('audience_id', $provider->id),
            ],
        ];
        $this->validate($request, $rule);
        try{
            \DB::beginTransaction();
            $id = null;
            if ($customerGeneralAudience = Audience::audienceType('customer')->first()){
                $id = $customerGeneralAudience->id;
            }

            $tagStore = new TagBlog;
            $tagStore->tag_name_ind = $request->get('tag_name_ind');
            $tagStore->tag_name_eng = $request->get('tag_name_eng');
            $tagStore->audience_id = $id;
            $tagStore->save();
            msg('Blog Tag created');
            \DB::commit();
            return redirect()->back();
        }catch (\Exception $exception){
            \DB::rollBack();
            msg('Something Wrong');
            return redirect()->back();
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $provider = Audience::firstOrCreate(['audience_name' => 'customer']);
        $rule = [
            'id'=>'required|exists:tag_blogs,id',
            'tag_name_eng' => [
                'required',
                Rule::unique('tag_blogs','tag_name_eng')->whereNot('id',$request->id)->where('audience_id',$provider->id)
            ],
            'tag_name_ind' => [
                'required',
                Rule::unique('tag_blogs','tag_name_ind')->whereNot('id',$request->id)->where('audience_id',$provider->id)
            ]
        ];
        $this->validate($request, $rule);
        $tagUpdate = TagBlog::withGlobalScope('customer',new AudienceCustomerGeneralScope())->where('id',$request->id)->first();
        if ($tagUpdate) {
            $tagUpdate->update([
                'tag_name_eng'=>$request->input('tag_name_eng'),
                'tag_name_ind'=>$request->input('tag_name_ind')
            ]);
            msg('Tag Blog Updated!');
            return redirect()->back();
        }
        msg('Tag Blog not found!', 2);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $tagDelete = TagBlog::withGlobalScope('customer',new AudienceCustomerGeneralScope())->where('id',$request->id)->first();
        if ($tagDelete) {
            $tagDelete->delete();
            msg('Tag Blog Deleted!');
            return redirect()->back();
        }
        msg('Tag Blog not found!', 2);
        return redirect()->back();
    }
}
