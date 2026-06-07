<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Cek apakah user sudah login
        if (!$request->user()) {
            return redirect('/login');
        }
        
        // Ambil nama role user saat ini
        $userRole = $request->user()->role?->name;
        
        // Cek apakah user memiliki salah satu role yang diizinkan
        if (in_array($userRole, $roles)) {
            return $next($request);
        }
        
        // Jika tidak punya akses, tampilkan error 403
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}