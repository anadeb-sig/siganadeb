<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PublicApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Ajoutez des conditions pour déterminer si une route est publique
        if($request->is('public/api/*')) {
            return $next($request);
        }
        return $next($request)->middleware('auth');
    }
}
