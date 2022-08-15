<?php

namespace App\Http\Controllers\Backoffice\Master;

use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class GuideLanguageCtrl extends Controller
{
    /**
     * show guide language list
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        toastr();
        return view('back-office.page.master.guide-language.index');
    }

    /**
     * provide data for guide language datatable
     * @return mixed
     * @throws \Exception
     */
    public function loadData()
    {
        $models = Language::all();
        return \DataTables::of($models)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                $html = '<button data-name="' . $model->language_name . '" data-id="' . $model->id_language . '"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-visible"></i></button>';
                $html .= ' <button data-name="' . $model->language_name . '" data-id="' . $model->id_language . '" class="btn-delete btn btn-outline-danger btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air">';
                $html .= '<i class="fa fa-trash"></i>';
                $html .= '</button>';
                return $html;
            })
            ->make(true);
    }

    /**
     * save guide language
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rule = [
            'language_name' => 'required|unique:tbl_language,language_name'
        ];
        $this->validate($request, $rule);
        try{
            \DB::beginTransaction();
            $lang = Language::create(['language_name'=>$request->input('language_name')]);
            msg($lang->language_name.' created');
            \DB::commit();
            return redirect()->back();
        }catch (\Exception $exception){
            \DB::rollBack();
            dd($exception);
            msg('Something Wrong');
            return redirect()->back();
        }


    }

    /**
     * update guide language
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $rule = [
            'id_language'=>'required|exists:tbl_language,id_language',
            'language_name' => [
                'required',
                Rule::unique('tbl_language','language_name')->whereNot('id_language',$request->id_language)
            ]
        ];
        $this->validate($request, $rule);
        $lang = Language::find($request->id_language);
        if ($lang) {
            $lang->update(['language_name'=>$request->input('language_name')]);
            msg('Language Updated!');
            return redirect()->back();
        }
        msg('Language not found!', 2);
        return redirect()->back();
    }

    /**
     * delete guide language
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        $lang = Language::find($request->id_language);
        if ($lang) {
            $lang->delete();
            msg('Language Deleted!');
            return redirect()->back();
        }
        msg('Language not found!', 2);
        return redirect()->back();
    }
}
