<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ServerRestrictionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validApiKey = "c595640c96bbdf2adc12fe00d55ed3edb91454d7b54d6ac6af7d0b577b0f7c6fc05d7dbc7984db7dd2ab8920fba7349ff1baad0a98e9f74ef219b79f9d7a2305";
        $allowedServerIP = '127.0.0.1';
        $apiKey = $request->header('X-API-Key');
        if ($request->ip() !== $allowedServerIP || $apiKey != $validApiKey) {
            abort(403, "Forbidden");
        }
        return $next($request);
    }
}
