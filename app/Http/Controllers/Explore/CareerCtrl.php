<?php

namespace App\Http\Controllers\Explore;

use App\Models\GomodoCareer;
use App\Models\SeoPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CareerCtrl extends Controller
{
    public function index()
    {
        $data['title'] = 'Career';
        $data['seo'] = SeoPage::where('section_slug','career')->whereHas('category', function ($category){
            $category->where('category_page_slug','b2c-marketplace');
        })->first();
        return view('explore.page.careers.careers', $data);
    }

    public function ajaxLoadMore(Request $request)
    {
        $data['careers'] = GomodoCareer::where('active', 1)->paginate(4);

        return view('explore.render.career', $data);
    }

    public function detail($id)
    {
        $data['seo'] = SeoPage::where('section_slug','career')->whereHas('category', function ($category){
            $category->where('category_page_slug','b2c-marketplace');
        })->first();
        $data['career'] = GomodoCareer::where('active', 1)->where('id', $id)->first();
        if (!$data['career']) {
            return redirect()->route('explore.careers.index');
        }
        $data['title'] = 'Career';
        return view('explore.page.careers.detail', $data);
    }

    public function requestForm($id)
    {

        $data['career'] = GomodoCareer::where('active', 1)->where('id', $id)->first();
        if (!$data['career']) {
            return redirect()->route('explore.careers.index');
        }
        $data['title'] = 'Career';
        $data['seo'] = SeoPage::where('section_slug','career')->whereHas('category', function ($category){
            $category->where('category_page_slug','b2c-marketplace');
        })->first();
        return view('explore.page.careers.request', $data);
    }

    public function apply($id, Request $request)
    {
//        dd($request->all());
        $career = GomodoCareer::where('active', 1)->where('id', $id)->first();
        if (!$career) {
            return apiResponse(404, 'Career not found', ['redirect' => route('explore.careers.index')]);
        }
        $rules = [
            'full_name' => 'required|max:100',
            'email' =>'required|email|max:100',
            'phone_number' => 'required|max:20|phone:ID',
            'portfolio'=>'required|file|mimes:pdf|max:2000'
        ];
        $this->validate($request,$rules);

        try{
            \DB::beginTransaction();
            if (!\File::isDirectory(storage_path('app/public/uploads/career'))){
                \File::makeDirectory(storage_path('app/public/uploads/career'),0777,true,true);
            }
            $file = \Storage::disk('public')->putFileAs('uploads/career/'.$id,$request->file('portfolio'),str_slug($request->input('name')).generateRandomString().'.pdf');
            $career->applicants()->create([
                'full_name' => $request->input('full_name'),
                'email' => $request->input('email'),
                'phone_number' => $request->input('phone_number'),
                'portfolio'=>$file
            ]);
            \DB::commit();
            return apiResponse(200,'OK',['redirect'=>route('explore.careers.index')]);

        }catch (\Exception $exception){
            \DB::rollBack();
            return apiResponse(500,'Error',getException($exception));
        }


    }
}
