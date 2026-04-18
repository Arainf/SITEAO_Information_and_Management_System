<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Usage: ->middleware('role:admin')
     *        ->middleware('role:admin,moderator')
     *        ->middleware('role')  — active/non-pending gate only
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->isActive()) {
            abort(403, 'Your account has been deactivated. Please contact an administrator.');
        }

        if ($user->isPending()) {
            return redirect()->route('pending.notice');
        }

        if (! empty($roles) && ! $user->hasRole($roles)) {
            abort(403, 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}
