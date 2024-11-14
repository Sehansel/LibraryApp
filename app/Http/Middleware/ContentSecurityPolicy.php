<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $cspHeader = "Content-Security-Policy";
        $cspDirectives = "default-src 'self'; script-src 'self'; object-src 'none';";

        $response->headers->set($cspHeader, $cspDirectives);

        return $response;
    }
}
