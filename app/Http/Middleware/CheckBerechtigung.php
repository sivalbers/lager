<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBerechtigung
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $berechtigung): Response
    {
        $user = $request->user();

        if (!$user || !$user->hasBerechtigung($berechtigung)) {
            abort(403, 'Zugriff verweigert – keine Berechtigung: ' . $berechtigung);
        }

        return $next($request);
    }
}
