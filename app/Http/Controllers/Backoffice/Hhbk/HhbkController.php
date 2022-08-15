<?php

namespace App\Http\Controllers\Backoffice\Hhbk;

use App\Imports\HhbkImport;
use App\Models\Company;
use App\Models\Hhbk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class HhbkController extends Controller
{
    public function index()
    {
        toastr();
        return view('back-office.hhbk.index');
    }

    public function importExcell(Request $request)
    {
        $rules = [
            'upload' => 'required|file'
        ];
        $this->validate($request, $rules);
        $excell = $request->file('upload');
        $array = new HhbkImport();
        $array->import($excell);
        return redirect()->back();

    }

    public function getData()
    {
        $model = Hhbk::query()->with(['company']);
        return DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                $html = '<a href="' . route('admin:hhbk.edit', ['id' => $model->id]) . '" data-name="' . $model->product_name . '" data-id="' . $model->id . '"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air"><i class="fa flaticon-visible"></i></a>';
                $html .= ' <button  data-name="' . $model->product_name . '" data-id="' . $model->id . '"  class="btn btn-outline-danger btn-delete btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air"><i class="fa flaticon-delete"></i></button>';
                return $html;
            })
            ->make(true);
    }

    public function add()
    {
        toastr();
        return view('back-office.hhbk.add');
    }

    public function save(Request $request)
    {
        $rules = [
            'id' => 'required|unique:hhbks,id',
            'product_name' => 'required',
            'city' => 'required',
            'domain' => 'nullable|exists:tbl_company,domain_memoria',
            'product_description' => 'nullable',
            'product_detail' => 'nullable'
        ];
        $this->validate($request, $rules);
        $company_id = null;
        if ($company = Company::whereDomainMemoria($request->input('domain'))->first()):
            $company_id = $company->id_company;
        endif;
        $data = [
            'id' => $request->input('id'),
            'city' => $request->input('city'),
            'company_id' => $company_id,
            'product_name' => $request->input('product_name'),
            'product_description' => $request->input('product_description'),
            'product_detail' => $request->input('product_detail')
        ];
        try {
            \DB::beginTransaction();
            Hhbk::create($data);
            msg('HHBK created');
            \DB::commit();
            return redirect()->route('admin:hhbk.index');
        } catch (\Exception $exception) {
            msg($exception->getMessage(), 2);
            return redirect()->back();
        }
    }

    public function edit(Request $request)
    {
        toastr();
        $data['hhbk'] = Hhbk::find($request->get('id'));
        if ($data['hhbk']):
            return view('back-office.hhbk.edit', $data);
        else:
            msg('HHBK not found');
            return redirect()->route('admin:hhbk.index');
        endif;
    }

    public function update(Request $request)
    {
        if ($request->filled('domain')):
            $request->merge([
               'domain'=>str_replace(['http://','https://'],'',$request->input('domain') )
            ]);
        endif;
        $rules = [
            'id' => 'required|exists:hhbks,id',
            'product_name' => 'required',
            'city' => 'required',
            'domain' => 'nullable|exists:tbl_company,domain_memoria',
            'product_description' => 'nullable',
            'product_detail' => 'nullable'
        ];
        $this->validate($request, $rules);
        $company_id = null;
        if ($company = Company::whereDomainMemoria($request->input('domain'))->first()):
            $company_id = $company->id_company;
        endif;
        $data = [
            'city' => $request->input('city'),
            'company_id' => $company_id,
            'product_name' => $request->input('product_name'),
            'product_description' => $request->input('product_description'),
            'product_detail' => $request->input('product_detail')
        ];
        try {
            \DB::beginTransaction();
            $hhbk = Hhbk::find($request->input('id'));
            if ($hhbk):
                $hhbk->update($data);
                msg('HHBK update');
                \DB::commit();
                return redirect()->route('admin:hhbk.index');
            else:
                msg('HHBK not found', 2);
                return redirect()->route('admin:hhbk.index');
            endif;
        } catch (\Exception $exception) {
            msg($exception->getMessage(), 2);
            return redirect()->back();
        }
    }

    public function delete(Request $request)
    {
        $rules = [
            'id' => 'required|exists:hhbks,id'
        ];
        $this->validate($request, $rules);
        $hhbk = Hhbk::find($request->input('id'));
        if ($hhbk):
            $hhbk->delete();
            msg('HHBK deleted');
            \DB::commit();
            return redirect()->route('admin:hhbk.index');
        else:
            msg('HHBK not found', 2);
            return redirect()->route('admin:hhbk.index');
        endif;

    }
}
