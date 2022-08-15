<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DomainAvailable extends Controller
{
    //
    function index(){

        return view('public/memoria/register');
    }
    
    function checkEmail(Request $request)
    {
        if($request->get('email'))
        {
            $email = $request->get('email');
            $data = DB::table("tbl_user_agent")
            ->where('email', $email)
            ->count();
            if($data>0)
            {
                echo 'not_unique';
            }
            else
            {
                echo 'unique';
            }
        }
    }

   function checkDomain(Request $request)
    {
        if($request->get('domain_memoria'))
        {
            $domain_memoria = $request->get('domain_memoria');
            $data = DB::table("tbl_company")
            ->where('domain_memoria', $domain_memoria)
            ->count();
            if($data>0)
            {
                echo 'not_unique';
            }
            else
            {
                echo 'unique';
            }
        }
    }
}
