<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForceLogoutIfMaintenance
{
    public function handle(Request $request, Closure $next)
    {
        if (app()->isDownForMaintenance() && Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with('message', 'Aplikasi sedang dalam pemeliharaan. Silakan coba lagi nanti.');
        }

        return $next($request);
    }
}