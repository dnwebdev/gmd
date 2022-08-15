<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyNotifController extends Controller
{
    
	var $company = 0;

	private function initalize(Request $request){
        $user = \Auth::user();
        $this->user = $user->id_user_agen;
        $this->company = $user->id_company;
        
    }


    public function read_notif(Request $request){
    	$this->initalize($request);

    	$notif_id = $request->segment(3);
    	if(!empty($notif_id)){
    		$notif = \App\Models\CompanyNotification::find($notif_id);
    		if($notif){
    			$notif->status = 0;
    			$notif->save();

    			return redirect()->intended($notif->slug);
    		}
    		
    	}
    }

    public function my_notification(Request $request){
    	$this->initalize($request);

    	$notif = \App\Models\CompanyNotification::where(['id_user_agent'=>$this->user,'status'=>1])->orderBy('created_at','desc')->get();
    	return response()->json([
                                    'status' => 200,
                                    'message' => 'ok',
                                    'data' => ['notification'=>$notif]
                                ]);
    }
}
