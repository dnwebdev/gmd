<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\BankList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BankListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bank_list= BankList::all();
        return view('dashboard.company.bank.create', ['bank_lists' => $bank_list]);
    }


   
}
