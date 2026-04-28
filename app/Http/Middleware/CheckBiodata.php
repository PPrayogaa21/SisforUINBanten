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
            if (!$request->is('biodata*') && !$request->is('logout')) {
                return redirect()->route('biodata.create')
                    ->with('warning', 'Silakan lengkapi biodata Anda terlebih dahulu.');
            }
        }

        return $next($request);
    }
}
