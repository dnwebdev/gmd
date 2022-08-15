<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Resources\User\UserAgentResource;
use App\Models\UserAgent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function detail(Request $request)
    {
        return apiResponse(200,
            'OK',
            UserAgentResource::make(
                UserAgent::with('company')->where(
                    'id_user_agen',
                    auth('api')->id()
                )->first()
            )
        );
    }
}
