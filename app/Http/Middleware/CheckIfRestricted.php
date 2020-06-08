<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIfRestricted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->hasRole('Restricted')) {
            abort(403, 'You are currently restricted from accessing authenticated portions of the Gander Oceanic website. Contact the OCA Chief for more information.');
        } else {
            return $next($request);
        }

    }
}
