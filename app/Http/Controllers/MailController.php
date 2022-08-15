<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Jobs\SendEmail;

class MailController extends Controller
{
    /**
     * function contact send mail
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function mail(Request $request) {
        $subject = "Contact Us Form";
        $to = 'support@mygomodo.com';
        $message = 'You have received a new message from your website contact form. 
        Here are the details, Name: '.$request->input('name').', Email: '.$request->input('email').
        ', Phone: '.$request->input('phone').', Message: '.$request->input('message');
        $data = ['body' => $message];
        $template = view('basic_email', $data)->render();
        dispatch(new SendEmail($subject, $to, $template));

        return json_encode(['status'=>200,'message'=>'Contact form submmitted!']);
    }
}
