<?php

namespace App\Services;
use Illuminate\Http\Request;
use Mail;

class UtilityService {

	/**
	 * function make pdf
	 *
	 * @param  mixed $view
	 *
	 * @return void
	 */
	public function make_pdf($view=''){
		//$pdf = \PDF::loadView('public.agents.admiria.invoicepdf',['company'=>$company,'order'=>$order]);
        $pdf = \PDF::loadHTML($view);
        return $pdf;


	}

	/**
	 * process function send mail backup
	 *
	 * @param  mixed $to
	 * @param  mixed $subject
	 * @param  mixed $view_message
	 * @param  mixed $sender_name
	 * @param  mixed $attached
	 * @param  mixed $conf
	 *
	 * @return void
	 */
	public function send_mail_backup($to,$subject,$view_message,$sender_name='Gomodo Admin',$attached=null,$conf=null){

		if($conf == null){

			$conf = [
	                'driver' => env('DRIVER'),
	                'host' => env('MAIL_HOST'),
	                'port' => env('MAIL_PORT'),
	                'username' => env('MAIL_USERNAME'),
	                'password' => env('MAIL_PASSWORD')
	                ];
        }

        \Config::set('mail', $conf);

        $app = \App::getInstance();
        $app->register('Illuminate\Mail\MailServiceProvider');

        //'public.agents.admiria.booking-email', ['company'=>$company]
        // \Mail::send($view_message,$view_data, function($msg) use($to,$subject,$sender_name,$conf,$attached){
        \Mail::raw([],[], function($msg) use($to,$subject,$sender_name,$conf,$attached){
        	$msg->setBody($view_message, 'text/html');
           	$msg->subject($subject);
           	$msg->from($conf['username'], $sender_name);
           	$msg->to($to);
           	if($attached != null){
           		$msg->attachData($attached['data']->output(), $attached['name']);
       		}
        });
	}

	/**
	 * process function send ail
	 *
	 * @param  mixed $to
	 * @param  mixed $subject
	 * @param  mixed $view_message
	 * @param  mixed $email_data
	 * @param  mixed $sender_name
	 * @param  mixed $attached
	 * @param  mixed $conf
	 *
	 * @return void
	 */
	public function send_mail($to,$subject,$view_message, $email_data, $sender_name='Gomodo Admin',$attached=null,$conf=null){
		Mail::send($view_message, $email_data, function ($message) use ($subject, $to) {
			$message->subject($subject);
			$message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
			$message->to($to);
		});
	}

	/**
	 * process function send mail no view
	 *
	 * @param  mixed $to
	 * @param  mixed $subject
	 * @param  mixed $body
	 * @param  mixed $attached
	 *
	 * @return void
	 */
	public function send_mail_no_view($to, $subject, $body, $attached=null){
		Mail::send([], [], function ($message) use ($subject, $body, $to, $attached) {
			$message->subject($subject);
			$message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
			$message->setBody($body);
			if ($attached != null) {
				$message->attach($attached->getRealPath(), [
				'as' => $attached->getClientOriginalName(),
				'mime' => $attached->getClientMimeType(),
			]);
			}
			$message->to($to);
		});
	}


	/**
	 * process function format phone
	 *
	 * @param  mixed $phone
	 *
	 * @return void
	 */
	public function format_phone($phone){
		if(strlen($phone) > 0){
			$f = $phone[0];
			if($f == '0'){
				$phone = '62'.substr($phone, 1);
			}
		}

		return $phone;
	}


	/**
	 * process function format human time
	 *
	 * @param  mixed $time1
	 * @param  mixed $time2
	 * @param  mixed $precision
	 *
	 * @return void
	 */
	function format_human_time($time1, $time2, $precision = 2) {
		// If not numeric then convert timestamps
		if( !is_int( $time1 ) ) {
			$time1 = strtotime( $time1 );
		}
		if( !is_int( $time2 ) ) {
			$time2 = strtotime( $time2 );
		}
		// If time1 > time2 then swap the 2 values
		if( $time1 > $time2 ) {
			list( $time1, $time2 ) = array( $time2, $time1 );
		}
		// Set up intervals and diffs arrays
		$intervals = array( 'year', 'month', 'day', 'hour', 'minute', 'second' );
		$diffs = array();
		foreach( $intervals as $interval ) {
			// Create temp time from time1 and interval
			$ttime = strtotime( '+1 ' . $interval, $time1 );
			// Set initial values
			$add = 1;
			$looped = 0;
			// Loop until temp time is smaller than time2
			while ( $time2 >= $ttime ) {
				// Create new temp time from time1 and interval
				$add++;
				$ttime = strtotime( "+" . $add . " " . $interval, $time1 );
				$looped++;
			}
			$time1 = strtotime( "+" . $looped . " " . $interval, $time1 );
			$diffs[ $interval ] = $looped;
		}
		$count = 0;
		$times = array();
		foreach( $diffs as $interval => $value ) {
			// Break if we have needed precission
			if( $count >= $precision ) {
				break;
			}
			// Add value and interval if value is bigger than 0
			if( $value > 0 ) {
				if( $value != 1 ){
					$interval .= "s";
				}
				// Add value and interval to times array
				$times[] = $value . " " . $interval;
				$count++;
			}
		}
		// Return string with times
		return implode( ", ", $times );
	}
}
