<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SellerMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login dan rolenya adalah 'Seller'
        if (auth()->check() && auth()->user()->isSeller()) {
            return $next($request);
        }

        // Jika bukan seller, cegah akses
        abort(403, 'Akses ditolak. Halaman ini khusus untuk Penjual (Seller).');
    }
}
