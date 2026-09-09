<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login dan rolenya adalah 'Admin'
        if (auth()->check() && auth()->user()->role === 'Admin') {
            return $next($request);
        }

        // Jika bukan admin, tendang ke halaman home atau beri pesan error 403
        abort(403, 'Unauthorized action. Halaman ini khusus Admin.');
    }
}
