<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Blocks administrators from guest/student-only marketplace flows if needed.
 */
class EnsureNotAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            abort(403, 'Administrators cannot perform student marketplace actions.');
        }

        return $next($request);
    }
}
