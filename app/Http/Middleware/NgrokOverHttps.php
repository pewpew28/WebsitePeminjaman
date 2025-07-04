<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL; // Penting: Pastikan ini ada
use Symfony\Component\HttpFoundation\Response;

class NgrokOverHttps
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah host adalah domain ngrok
        if (str_ends_with($request->getHost(), '.ngrok-free.app')) {
            URL::forceScheme('https'); // Paksa skema HTTPS untuk semua URL yang dihasilkan Laravel
        }

        return $next($request);
    }
}