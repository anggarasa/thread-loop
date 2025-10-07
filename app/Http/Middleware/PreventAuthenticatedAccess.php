<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreventAuthenticatedAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is authenticated, redirect them to the authenticated version of the page
        if (Auth::check()) {
            $routeName = $request->route()->getName();

            // Map guest routes to their authenticated equivalents
            $redirectMap = [
                'guest.home' => 'homePage',
                'guest.search' => 'searchPage',
            ];

            if (isset($redirectMap[$routeName])) {
                return redirect()->route($redirectMap[$routeName]);
            }

            // Default redirect to home if no specific mapping
            return redirect()->route('homePage');
        }

        return $next($request);
    }
}
