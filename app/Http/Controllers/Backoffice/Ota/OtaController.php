<?php

namespace App\Http\Controllers\Backoffice\Ota;

use App\Models\Ota;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OtaController extends Controller
{
    public function index()
    {
        toastr();
        return view('back-office.page.ota.index');
    }

    public function loadData()
    {
        return \DataTables::of(Ota::all())
            ->addIndexColumn()
            ->editColumn('ota_icon', function ($model) {
                return '<img width=80 src="' . asset($model->ota_icon) . '">';
            })
            ->editColumn('ota_status', function ($model) {
                if ($model->ota_status == '0') {
                    return '<span class="label label-default">Inactive</span>';
                }
                return '<span class="label label-success">Active</span>';
            })
            ->addColumn('action', function ($model) {
                $html = '<a href="' . route('admin:ota.edit', ['id' => $model->id]) . '" data-name="' . $model->ota_name . '" data-id="' . $model->id . '"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-visible"></i></a>';
                $html .= ' <button data-name="' . $model->ota_name . '" data-id="' . $model->id . '"  class="btn btn-outline-danger btn-delete btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-delete"></i></button>';

                return $html;

            })
            ->rawColumns(['action', 'ota_icon', 'ota_status'])
            ->make(true);
    }

    public function add()
    {
        return view('back-office.page.ota.add');
    }

    public function store(Request $request)
    {
        $rules = [
            'ota_name' => 'required',
            'ota_slug' => 'required|unique:otas,ota_slug',
            'ota_icon' => 'required|file|mimes:png,jpg,jpeg',
            'ota_status' => 'required|in:0,1,2',
            'ota_original_markup'=>'numeric|min:0|max:50',
            'ota_gomodo_markup'=>'numeric|min:0|max:50'
        ];
        $this->validate($request, $rules);
        try {
            \DB::beginTransaction();;
            $new = new Ota();
            $new->ota_name = $request->input('ota_name');
            $new->ota_slug = $request->input('ota_slug');
            $new->ota_status = $request->input('ota_status');
            $new->ota_original_markup = $request->input('ota_original_markup');
            $new->ota_gomodo_markup = $request->input('ota_gomodo_markup');
            if ($request->hasFile('ota_icon')) {
                $icon = $request->file('ota_icon');
                $path = storage_path('app/public/uploads/ota/');
                $name = time() . '-' . $new->ota_slug . '.' . $icon->getClientOriginalExtension();
                if (!\File::isDirectory($path)) {
                    \File::makeDirectory($path, 0777, true, true);
                }
                \Image::make($icon)->save($path . $name);
                $new->ota_icon = 'storage/uploads/ota/' . $name;
            }
            $new->save();
            \DB::commit();
            return apiResponse(200, 'Success Created Data OTA', ['redirect' => route('admin:ota.index')]);
        } catch (\Exception $exception) {
            \DB::rollBack();
            return apiResponse(500, $exception->getMessage());
        }
    }

    public function edit($id)
    {
        $ota = Ota::find($id);
        if (!$ota) {
            msg('OTA Not found', 2);
            return redirect()->route('admin:ota.index');
        }
        return view('back-office.page.ota.edit', compact('ota'));
    }

    public function update($id, Request $request)
    {
        $ota = Ota::find($id);
        if (!$ota) {
            return apiResponse(404, 'OTA not Found', ['redirect' => route('admin:ota.index')]);
        }

        $rules = [
            'ota_name' => 'required',
            'ota_slug' => 'required|unique:otas,ota_slug,' . $id,
            'ota_icon' => 'nullable|file|mimes:png,jpg,jpeg',
            'ota_status' => 'required|in:0,1,2',
            'ota_original_markup'=>'numeric|min:0|max:50',
            'ota_gomodo_markup'=>'numeric|min:0|max:50'
        ];
        $this->validate($request, $rules);
        $deleteIcon = null;
        try {
            \DB::beginTransaction();;
            $ota->ota_name = $request->input('ota_name');
            $ota->ota_slug = $request->input('ota_slug');
            $ota->ota_status = $request->input('ota_status');
            $ota->ota_original_markup = $request->input('ota_original_markup');
            $ota->ota_gomodo_markup = $request->input('ota_gomodo_markup');
            if ($request->hasFile('ota_icon')) {
                $icon = $request->file('ota_icon');
                $path = storage_path('app/public/uploads/ota/');
                $name = time() . '-' . $ota->ota_slug . '.' . $icon->getClientOriginalExtension();
                if (!\File::isDirectory($path)) {
                    \File::makeDirectory($path, 0777, true, true);
                }
                if(\Image::make($icon)->save($path . $name)){
                    $deleteIcon = $ota->icon;
                    $ota->ota_icon = 'storage/uploads/ota/' . $name;
                }

            }
            $ota->save();
            \DB::commit();
            if ($deleteIcon && \File::exists($deleteIcon)){
                \File::delete($deleteIcon);
            }
            return apiResponse(200, 'Success Updated Data OTA', ['redirect' => route('admin:ota.index')]);
        } catch (\Exception $exception) {
            \DB::rollBack();
            return apiResponse(500, $exception->getMessage());
        }
    }

    public function delete(Request $request)
    {
        $ota = Ota::find($request->id);
        if (!$ota) {
            msg('OTA Not found', 2);
            return redirect()->route('admin:ota.index');
        }
        $ota->delete();
        msg('Success Delete Data OTA');
        return redirect()->route('admin:ota.index');
    }
}
