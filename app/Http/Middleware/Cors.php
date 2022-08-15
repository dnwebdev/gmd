<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    public function handle($request, Closure $next)
    {
        $return = $next($request)
          ->header('Access-Control-Allow-Origin', '*')
          ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');

        if ($request->hasHeader('referer')){
            $return = $return->header('X-Frame-Options', 'ALLOWALL');
        }
        return $return;
    }
}