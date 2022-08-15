<?php

namespace App\Http\Controllers\Explore;

use App\Models\NewsLetter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsLetterCtrl extends Controller
{
    public function subscribe(Request $request)
    {
        $rule = [
            'email' => 'required|email'
        ];

        $this->validate($request, $rule);
        $email = strtolower($request->input('email'));
        $subscribedUser = NewsLetter::where('email', $email)->where('type', 'public')->first();
        if (!$subscribedUser) {
            NewsLetter::create([
                'email' => $email,
                'type' => 'public'
            ]);
            return apiResponse(200, 'Subscribed!');
        }
        if ($subscribedUser->status == 'subscribed') {
            return apiResponse(403, 'Already Subscribed');
        }

        $subscribedUser->update(['status' => 'subscribed']);
        return apiResponse(200, 'Subscribed!');
    }
}
