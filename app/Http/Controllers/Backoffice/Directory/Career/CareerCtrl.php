<?php

namespace App\Http\Controllers\Backoffice\Directory\Career;

use App\Models\GomodoCareer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CareerCtrl extends Controller
{
    public function index()
    {
        toastr();
        return view('back-office.page.directory.career.index');
    }

    public function loadData()
    {
        $model = GomodoCareer::query()->withCount('applicants');
        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                $html = '<a href="' . route('admin:directory.career.edit', ['id' => $model->id]) . '"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-visible"></i></a>';
                $html .= ' <button data-name="' . $model->title . '" data-id="' . $model->id . '" class="btn-delete btn btn-outline-danger  btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air">';
                $html .= '<i class="fa fa-trash"></i>';
                $html .= '</button>';
                return $html;
            })
            ->make(true);
    }

    public function create()
    {
        toastr();

        return view('back-office.page.directory.career.add');
    }

    public function save(Request $request)
    {

        $rules = [
            'title' => 'required',
            'description' => 'required',
            'location' => 'required',
        ];

        $this->validate($request, $rules);
        try {
            \DB::beginTransaction();
            $career = GomodoCareer::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'location' => $request->input('location'),
                'active' => $request->has('active') ? $request->input('active') : false
            ]);

            \DB::commit();
            return apiResponse(200, 'OK ', ['redirect' => route('admin:directory.career.index')]);
        } catch (\Exception $exception) {
            \DB::rollBack();

            return apiResponse(500, __('general.whoops'), getException($exception));
        }
    }

    public function edit($id)
    {
        if (!$data['career'] = GomodoCareer::find($id)) {
            msg('Career not Found', 2);
            return redirect()->route('admin:directory.career.index');
        }

        return view('back-office.page.directory.career.edit', $data);
    }

    public function update($id, Request $request)
    {
        if (!$career = GomodoCareer::find($id)) {
            msg('Career not Found', 2);
            return redirect()->route('admin:directory.career.index');
        }
        $rules = [
            'title' => 'required',
            'description' => 'required',
            'location' => 'required',
        ];

        $this->validate($request, $rules);
        try {
            \DB::beginTransaction();
            $career->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'location' => $request->input('location'),
                'active' => $request->has('active') ? $request->input('active') : false
            ]);

            \DB::commit();
            return apiResponse(200, 'OK ', ['redirect' => route('admin:directory.career.index')]);
        } catch (\Exception $exception) {
            \DB::rollBack();

            return apiResponse(500, __('general.whoops'), getException($exception));
        }
    }

    public function delete(Request $request)
    {
        if (!$career = GomodoCareer::find($request->id)) {
            msg('Career not Found', 2);
            return redirect()->route('admin:directory.career.index');
        }
        $career->delete();
        msg('Career deleted');
        return redirect()->route('admin:directory.career.index');
    }
}
