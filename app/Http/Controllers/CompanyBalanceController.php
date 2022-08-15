<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyBalanceController extends Controller
{

    var $company = 0;

    /**
     * __construct
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('host');
        $this->middleware('auth');
        //$this->middleware('company');
        
    }

    /**
     * Function initalize get data user
     *
     * @param  mixed $request
     *
     * @return void
     */
    private function initalize(Request $request){
        $user = \Auth::user();
        $this->user = $user->id_user_agen;
        $this->company = $user->id_company;
        // $this->company = $request->get('my_company');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->initalize($request);

        $start_date = date('Y-m-1');
        $end_date = date('Y-m-t');
        $balance = \App\Models\Journal::where(['id_company'=>$this->company])->get();

        $journal_service = app('\App\Services\JournalService');
        $total_balance = json_decode($journal_service->get_company_total_balance($this->company)->getContent());
        
        $total_balance = $total_balance->data;
        return view('dashboard.company.balance.index',['balance'=>$balance,'total_balance'=>$total_balance ? $total_balance->total_balance : 0]);
    }

    /**
     * function get total balance journal
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function get_total_balance(Request $request){
        $this->initalize($request);
        $journal_service = app('\App\Services\JournalService');
        $total_balance = json_decode($journal_service->get_company_total_balance($this->company)->getContent());
        
        $bal = $total_balance->data;
        
        return response()->json([
                                'status' => 200,
                                'message' => 'ok',
                                'data' => ['total_balance'=>$total_balance ? number_format($bal->total_balance,0) : 0],
                                
                            ]);
    }
    


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
