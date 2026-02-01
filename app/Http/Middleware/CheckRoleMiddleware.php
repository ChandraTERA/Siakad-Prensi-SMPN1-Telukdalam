<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        //cek apakah peran user ada di dalam daftar role yang diizinkan
        $user=Auth::user();
        foreach ($roles as $role) {
            if ($user->role == $role) {
                return $next($request);
            }
        }
        return redirect()->route('error.unauthorized');
    }
}
