<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsHost
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isHost()) {
            abort(403, 'Access denied. Host role required.');
        }

        return $next($request);
    }
}
