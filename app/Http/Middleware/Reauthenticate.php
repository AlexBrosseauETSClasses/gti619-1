<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class Reauthenticate
{
   public function handle($request, \Closure $next)
{
    $lastReauth = session('reauthenticated_at');

    if (!$lastReauth || now()->diffInMinutes($lastReauth) > 5) {
        session(['url.intended' => secure_url(request()->getRequestUri())]);
        return redirect()->route('reauth.show');
    }

    return $next($request);
}
}

