<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restricts access to verified students. Administrators are allowed through.
 */
class EnsureVerifiedStudent
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->isAdmin()) {
            return $next($request);
        }

        if (! $user->isVerified()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your student account must be verified before you can perform this action.',
                ], 403);
            }

            return redirect()->route('verification.status')
                ->with('warning', 'You must verify your Student ID before you can buy or sell on CampusBazar.');
        }

        return $next($request);
    }
}
