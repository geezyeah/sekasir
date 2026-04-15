<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventDuplicateShift
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Allow admins to have multiple sessions
        if ($user && $user->isAdmin()) {
            return $next($request);
        }

        // For non-admin users, only prevent starting a new shift if they have an active one
        // Allow them to view shifts.select to see their active shift info
        if ($user && $user->activeShift && $request->routeIs('shifts.start')) {
            return redirect()->route('shifts.select')
                ->with('info', __('pos.duplicate_shift_warning'));
        }

        return $next($request);
    }
}
