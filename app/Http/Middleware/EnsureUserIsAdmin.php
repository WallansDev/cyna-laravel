<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifie si l'utilisateur est authentifié et s'il a le rôle "admin"
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        // Si l'utilisateur n'est pas admin, on peut le rediriger ou répondre avec une erreur
        Auth::logout();

        // abort(403);
        return response()->view('auth.login');
        // redirect(route('login'));
    }
}
