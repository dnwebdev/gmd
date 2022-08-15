<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TaxFormRequest;
use \App\Models\Tax;

class CompanyTaxController extends Controller
{
    var $company = 0;

    public function __construct(Request $request)
    {
        $this->middleware('host');
        $this->middleware('auth');
        //$this->middleware('company');
        
    }

    private function initalize(Request $request){
        $user = \Auth::user();
        $this->user = $user->id_user_agen;
        $this->company = $user->id_company;
        // $this->company = $request->get('my_company');
    }

    public function index(Request $request){
    	$this->initalize($request);

        $tax = \App\Models\Tax::where('id_company',$this->company)->get();
        

        return view('dashboard.company.tax.index'
                        ,['tax'=>$tax,
                            
                        ]
                    );
    }

    public function create()
    {
        return view('dashboard.company.tax.create'); 
    }

    public function store(TaxFormRequest $request){
        $this->initalize($request);
        
        $status = $request->get('status');
        if(empty($status)){
            $status = 0;
        }

        $new_instance =Tax::create([
                                        'tax_name'=>$request->get('tax_name'),
                                        'tax_amount'=>$request->get('tax_amount'),
                                        'tax_amount_type'=>$request->get('tax_amount_type'),
                                        'id_company'=>$this->company,
                                        'status'=>$status,
                                    ]);

        return json_encode(['status'=>200,'message'=>'New Tax Created']);
    }

    public function edit(Tax $tax){
        return view('dashboard.company.tax.edit',['tax'=>$tax]);
    }

    public function update(TaxFormRequest $request, Tax $tax)
    {
        $this->initalize($request);
        
        \DB::transaction(function () use($tax,$request) {

            if($tax->id_company != $this->company){
                return response()->json([
                                        'status' => 400,
                                        'message' => 'Action not allowed'
                                    ]);
                                    
            }

            Tax::where(['id_company'=>$this->company
                        ,'id_tax'=>$tax->id_tax])
                ->update([
                            'tax_name'=>$request->get('tax_name'),
                            'tax_amount'=>$request->get('tax_amount'),
                            'tax_amount_type'=>$request->get('tax_amount_type') ,
                            'status'=> $request->get('status'),
                        ]);

            
            
        });

        return response()->json([
                                    'status' => 200,
                                    'message' => 'Tax Saved'
                                ]);

    }

}
