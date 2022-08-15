<?php 

namespace App\Services;
use Illuminate\Http\Request;

class NotificationService {

	/**
	 * function process add company notification
	 *
	 * @param  mixed $id_user_agent
	 * @param  mixed $notif_type
	 * @param  mixed $slug
	 * @param  mixed $id_trx
	 * @param  mixed $desc
	 *
	 * @return void
	 */
	public function add_company_notif($id_user_agent,$notif_type,$slug,$id_trx,$desc){
		\DB::transaction(function () use($id_user_agent,$notif_type,$slug,$id_trx,$desc) {
			\App\Models\CompanyNotification::create([
				'id_user_agent'=>$id_user_agent,
				'notif_type'=>$notif_type,
				'slug'=>$slug,
				'id_trx'=>$id_trx,
				'status'=>1,
				'description'=>$desc,
			]);
		});
        
	}

	
}