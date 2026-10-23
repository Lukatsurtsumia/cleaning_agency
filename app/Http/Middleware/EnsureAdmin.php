<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Keep every signed-in page to the agency's own accounts.
 *
 * Being logged in is not enough: the users table is shared with another app,
 * and accounts were open to self-registration until it was closed. Anyone not
 * on config('azurclean.admin_emails') is signed out and refused.
 */
class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! in_array(strtolower($user->email), config('azurclean.admin_emails'), true)) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            abort(403);
        }

        return $next($request);
    }
}
