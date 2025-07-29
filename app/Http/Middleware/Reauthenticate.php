<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class Reauthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::get('reauthenticated')) {
            Session::put('redirect_after_reauth', $request->fullUrl());
            return redirect()->route('reauth.show');
        }

        return $next($request);
    }
}

