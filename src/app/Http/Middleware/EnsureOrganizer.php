<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizer
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || $request->user()->role !== 'organizer') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk organizer.');
        }

        return $next($request);
    }
}
