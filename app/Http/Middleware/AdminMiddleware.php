<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect('/login'); // Atau rute login Anda
        }

        // Periksa apakah pengguna memiliki peran 'admin'
        if (Auth::user()->isAdmin()) { // Menggunakan metode isAdmin() dari model User
            return $next($request);
        }

        // Jika bukan admin, redirect atau tampilkan error
        return redirect('/dashboard')->with('error', 'Akses Ditolak: Anda tidak memiliki izin Admin.');
        // Atau: abort(403, 'Unauthorized access.');
    }
}