<?php

namespace App\Http\Controllers\Backoffice\Insurance;

use App\Models\InsuranceRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InsuranceRequestController extends Controller
{
    public function index()
    {
        return view('back-office.request.insurance');
    }

    public function getData()
    {
        $models = InsuranceRequest::query()->with('company');
        return \DataTables::of($models)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                return "<button class='btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air'><i class='fa flaticon-edit'></i></button>";
            })
            ->make(true);
    }
}
