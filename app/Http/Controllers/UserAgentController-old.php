<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserAgentController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return void
     */
    public function show()
    {
        return view('dashboard.company.user_agent.index',['user_agent' => \App\Models\UserAgent::skip(0)->take(10)->get()]);
    }
}
