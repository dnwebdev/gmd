<?php

namespace App\Http\Controllers\Backoffice\Insurance;

use App\Models\Insurance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InsuranceController extends Controller
{
    public function index()
    {
        $data['url_delete'] = route('admin:insurance.list.delete');
        $data['url_data'] = route('admin:insurance.list.data');
        $data['url_update'] = route('admin:insurance.list.status');
        return view('back-office.insurance.index', $data);
    }

    public function loadData()
    {
        $model = Insurance::all();
        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {

                if ($model->status == '0') {
                    $html = '<button type="button" data-id="'.$model->id.'" data-status="1" class="btn btn-outline-info btn-set-status btn-sm m-btn m-btn--icon m-btn--custom m-btn--pill m-btn--air">Set Active</button>';
                } else {
                    $html = '<button type="button" data-id="'.$model->id.'" data-status="0" class="btn btn-outline-danger btn-set-status btn-sm m-btn m-btn--icon m-btn--custom m-btn--pill m-btn--air">Set Non Active</button>';
                }

                return $html;
            })
            ->editColumn('status', function ($model) {
                if ($model->status == '0') {
                    return '<span class="badge badge-danger">Tidak Aktif</span>';
                }
                return '<span class="badge badge-success">Aktif</span>';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function create()
    {

    }

    public function store(Request $request)
    {

    }

    public function edit($id)
    {

    }

    public function update($id, Request $request)
    {

    }

    public function delete(Request $request)
    {

    }

    public function setStatus(Request $request)
    {
        if ($insurance = Insurance::find($request->get('id'))) {
            if ($insurance->status == $request->get('status') || !in_array($request->get('status'), ['0', '1'])) {
                if ($request->isXmlHttpRequest()) {
                    return apiResponse(403, 'Wrong Status');
                }
                msg('Wrong Status', 2);
                return redirect()->route('admin:insurance.list.index');
            }
            $insurance->update(['status' => $request->get('status')]);
            if ($request->isXmlHttpRequest()) {
                return apiResponse(200, 'OK');
            }
            msg('Insurance Updated');
            return redirect()->route('admin:insurance.list.index');
        }
        if ($request->isXmlHttpRequest()) {
            return apiResponse(404, 'Insurance not Found');
        }
        msg('Insurance not found', 2);
        return redirect()->route('admin:insurance.list.index');
    }
}
