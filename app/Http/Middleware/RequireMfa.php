<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireMfa
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! config('mailflow.require_mfa')) {
            return $next($request);
        }

        if (! $user->requiresMfa()) {
            return $next($request);
        }

        $verifiedAt = $request->session()->get('mfa_verified_at');

        if ($verifiedAt && now()->diffInMinutes($verifiedAt) < 60) {
            return $next($request);
        }

        return redirect()->route('mfa.challenge');
    }
}
