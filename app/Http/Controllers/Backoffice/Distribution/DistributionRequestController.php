<?php

namespace App\Http\Controllers\Backoffice\Distribution;

use App\Models\DistributionRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DistributionRequestController extends Controller
{
    public function index()
    {
        return view('back-office.request.distribution');
    }

    public function getData()
    {
        $models = DistributionRequest::query();
        return \DataTables::of($models)
            ->make(true);
    }
}
