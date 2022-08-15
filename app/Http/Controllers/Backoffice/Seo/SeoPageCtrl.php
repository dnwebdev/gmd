<?php

namespace App\Http\Controllers\Backoffice\Seo;

use App\Models\SeoPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SeoPageCtrl extends Controller
{
    public function index()
    {
        toastr();
        return view('back-office.page.seo.index');
    }

    public function loadData()
    {
        $model = SeoPage::with('category')->select('seo_pages.*');
        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                $html = '<a href="' . route('admin:seo.edit', ['id' => $model->id]) . '"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-visible"></i></a>';
                return $html;
            })
            ->make(true);
    }

    public function edit($id)
    {
        if (!$seo = SeoPage::find($id)) {
            msg('Seo Data not found', 2);
            return redirect()->route('admin:seo.index');
        }

        return view('back-office.page.seo.edit', compact('seo'));
    }

    public function update($id, Request $request)
    {
        if (!$seo = SeoPage::find($id)) {
            return apiResponse(404, 'Seo Data not found');
        }
        $rules = [
            'title' => 'required',
            'description' => 'required',
            'keywords' => 'required'
        ];
        $this->validate($request, $rules);
        $seo->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'keywords' => $request->input('keywords')
        ]);
        if ($request->wantsJson()):
            return apiResponse(200, 'OK', ['redirect' => route('admin:seo.index')]);
        else:
            msg('OK Updated');
            return redirect()->route('admin:seo.index');
        endif;

    }
}
