<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyEmailServer extends Controller
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

        $list_status = \App\Models\EmailServer::list_status();
        $mail = \App\Models\EmailServer::where('id_company',$this->company)->first();
        return view('dashboard.company.mail_server.index',['mail'=>$mail,'list_status'=>$list_status]);
    }

    
    
    
    public function update(Request $request)
    {
        $request = app('App\Http\Requests\MailServerFormRequest');
        $this->initalize($request);
        
        $mail_server = \App\Models\EmailServer::where(['id_company'=>$this->company])->first();

        $data = [
                'smtp_host'=>$request->get('smtp_host'),
                'smtp_port'=>$request->get('smtp_port'),
                'username'=>$request->get('username'),
                'password'=>$request->get('password'),
                'status'=>$request->get('status'),
                ];
        if($mail_server){
            
	        \DB::transaction(function () use($mail_server,$data) {
	         
	            $mail_server->update($data);
	        });
	    }
	    else{
	    	\DB::transaction(function () use($data) {
	         	$data['id_company'] = $this->company;
	            \App\Models\EmailServer::create($data);
	        });
	    }

        return response()->json([
                                'status' => 200,
                                'message' => 'Mail Server Saved'
                            ]);
        
    }
}
