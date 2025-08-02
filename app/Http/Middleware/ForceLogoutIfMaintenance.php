<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForceLogoutIfMaintenance
{
    public function handle(Request $request, Closure $next)
    {
        if (app()->isDownForMaintenance() && !app()->runningInConsole()) {
            if (request()->hasCookie('laravel_maintenance')) {
                // Biarkan user lewat, dia akses pakai secret link
                return $next($request);
            }

            Auth::logout(); // logout user lain
            return redirect()->route('login')->with('message', 'Aplikasi sedang maintenance');
        }

        return $next($request);
    }
}