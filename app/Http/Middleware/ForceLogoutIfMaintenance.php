<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceLogoutIfMaintenance
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jangan logout kalau sedang akses lewat secret key
        if (app()->isDownForMaintenance()) {
            $data = app()->maintenanceMode()->data();
            $secret = $data['secret'] ?? null;

            // Kalau ada secret dan user akses URL dengan secret, skip
            if ($secret && $request->is($secret) || $request->fullUrlIs('*' . $secret . '*')) {
                return $next($request);
            }

            // Kalau sudah login, logout paksa
            if (auth()->check()) {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
        }

        return $next($request);
    }
}