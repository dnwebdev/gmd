<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyMarkController extends Controller
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

        $mark = \App\Models\Mark::where('id_company',$this->company)->get();
        return view('dashboard.company.mark.index',['mark'=>$mark]);
    }

    
    public function create()
    {
        return view('dashboard.company.mark.create');
    }

    
    public function store(\App\Http\Requests\MarkFormRequest $request)
    {
        $this->initalize($request);
        $id = null;
        \DB::transaction(function () use($request,&$id) {
            $newmark = \App\Models\Mark::create([
                                                    'mark'=>$request->get('mark'),
                                                    'id_company'=>$this->company,
                                                    ]);
            $id = $newmark->id_mark;

        });

        return json_encode(['status'=>200,'message'=>'<a href="'.Route('company.mark.edit',$id).'">New Product Mark Created</a>']);
    }

    
    
    public function edit(Request $request,\App\Models\Mark $mark)
    {
        $this->initalize($request);
        if($mark->id_company != $this->company){
            return response()->json([
                                        'status' => 405,
                                        'message' => 'Action not allowed'
                                    ]);
        }
        return view('dashboard.company.mark.edit',['mark'=>$mark]);
    }

    
    public function update(\App\Models\Mark $mark)
    {
        $request = app('App\Http\Requests\MarkFormRequest');
        $this->initalize($request);
        $this->company = $request->get('my_company');
        
        if($mark->id_company != $this->company){
            return response()->json([
                                    'status' => 400,
                                    'message' => 'Action not allowed'
                                ]);
                                
        }

        \DB::transaction(function () use($mark,$request) {
         
            $mark->update([
                            'mark'=>$request->get('mark'),
                            ]);
        });

        return response()->json([
                                'status' => 200,
                                'message' => 'Product Mark Saved'
                            ]);
        
    }

}
