<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyCustomerController extends Controller
{
    

    var $company = 0;

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

        $customer = \App\Models\Customer::where('id_company',$this->company)->orderBy('created_at','desc')->skip(0)->take(20)->get();
        return view('dashboard.company.customer.index',['customer'=>$customer]);
    }

    public function load_more(Request $request){
        $this->initalize($request);
        $offset = !empty($request->segment(4))? $request->segment(4) : 0;
        $customer = \App\Models\Customer::where('id_company',$this->company)->orderBy('created_at','desc')->skip($offset)->take(20)->get();

        $view = view('dashboard.company.customer.search',['customer'=>$customer])->render();
        return response()->json([
                                    'status' => 200,
                                    'message' => 'ok',
                                    'data'=>['view'=>$view
                                            ,'offset'=>($offset+$customer->count())
                                            ]
                                ]);
    }

    public function search_by_email(Request $request){

        $this->initalize($request);


        $keyword = $request->get('query');
        $where = ['id_company'=>$this->company];
        
        if(!empty($keyword)){
            array_push($where,['email','LIKE','%'.$keyword.'%']);
        }

        $customer = \App\Models\Customer::where($where)->skip(0)->take(10)->get();
        $d_data = [];
        foreach($customer as $row){
            array_push($d_data,['value'=>$row->email
                                ,'data'=>$row->id_customer
                                ,'first_name'=>$row->first_name
                                ,'last_name'=>$row->last_name
                                ,'phone'=> $row->phone
                                ,'id_city' => $row->main_address ? $row->main_address->id_city : ''
                                ,'city_name' => $row->main_address ? $row->main_address->city->city_name : ''
                                ,'id_country' => $row->main_address ? $row->main_address->city->state->country->id_country : ''
                                ,'country_name' => $row->main_address ? $row->main_address->city->state->country->country_name : ''
                                ,'address'=> $row->main_address ? $row->main_address->address : ''
                    ]);
        }

        return response()->json([
                                'suggestions' => $d_data
                                
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
