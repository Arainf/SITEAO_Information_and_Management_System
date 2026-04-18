<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfPending
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isPending() && ! $request->routeIs('pending.notice', 'logout')) {
            return redirect()->route('pending.notice');
        }

        return $next($request);
    }
}
