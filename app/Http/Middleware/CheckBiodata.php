<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBiodata
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->biodata_verified) {
            $allowedRoutes = [
                'biodata.create', 'biodata.store',
                'logout',
                'dashboard', 'peserta.dashboard', 'narasumber.dashboard',
                'kegiatan.join'
            ];

            if (!$request->routeIs($allowedRoutes) && !$request->is('biodata*')) {
                return redirect()->route('biodata.create')
                    ->with('warning', 'Silakan lengkapi biodata Anda terlebih dahulu.');
            }
        }

        return $next($request);
    }
}
