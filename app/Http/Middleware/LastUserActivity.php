<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Events\UserActivityDetected; // Import event yang baru dibuat

class LastUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Memeriksa apakah ada pengguna yang sedang login
        if (Auth::check()) {
            // Jika ada, dispatch (lemparkan) event UserActivityDetected.
            // Pastikan untuk meneruskan ketiga argumen yang diharapkan oleh konstruktor event.
            event(new UserActivityDetected(
                Auth::user(),
                $request->ip(),
                $request->header('User-Agent') // Argumen ketiga yang sebelumnya hilang
            ));
        }

        // Lanjutkan request ke middleware berikutnya atau ke controller
        return $next($request);
    }
}
