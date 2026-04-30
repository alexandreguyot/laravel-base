<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        // Si 2FA activé pour ce user et pas encore validé dans cette session
        if ($user->two_factor_enabled && ! session('2fa_verified')) {
            // Pas de redirect en boucle
            if (! $request->routeIs('2fa.*')) {
                return redirect()->route('2fa.challenge');
            }
        }

        return $next($request);
    }
}
