<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;



class TwoFactor
{
    public function handle(Request $request, Closure $next)
    {
        // $user = $request->auth()->user();
        $user = Auth::user();


        if (Auth::check() && $user->two_factor_code) {
            if (now()->greaterThan($user->two_factor_expires_at)) {
                Auth::resetTwoFactorCode();
                Auth::logout();

                return redirect()->route('login')
                    ->with('message', 'The two factor code has expired. Please login again.');
            }

            if (!$request->is('verify*')) {
                return redirect()->route('verify.index');
            }
        }

        return $next($request);
    }
}
