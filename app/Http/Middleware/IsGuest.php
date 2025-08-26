<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsGuest
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isGuest()) {
            abort(403, 'Access denied. Guest role required.');
        }

        return $next($request);
    }
}
