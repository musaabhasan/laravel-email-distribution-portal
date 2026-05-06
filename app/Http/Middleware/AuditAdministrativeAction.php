<?php

namespace App\Http\Middleware;

use App\Models\AuditEvent;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditAdministrativeAction
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->user() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            AuditEvent::query()->create([
                'user_id' => $request->user()->id,
                'event' => strtolower($request->method()).':'.$request->route()?->getName(),
                'ip_address' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
                'metadata' => [
                    'path' => $request->path(),
                    'status' => $response->getStatusCode(),
                ],
            ]);
        }

        return $response;
    }
}
