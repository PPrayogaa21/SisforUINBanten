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
            $protectedRoutes = [
                'peserta.kegiatan.show',
                'peserta.kegiatan.absen',
                'peserta.kuesioner.fill',
                'peserta.kuesioner.submit',
                'narasumber.kegiatan.show',
                'narasumber.kegiatan.materi.upload',
            ];

            if ($request->routeIs($protectedRoutes)) {
                // Determine where to redirect back to
                $fallback = auth()->user()->role === 'narasumber' ? 'narasumber.kegiatan.index' : 'peserta.kegiatan.index';
                
                return redirect()->route($fallback)
                    ->with('show_biodata_modal', true);
            }
        }

        return $next($request);
    }
}
