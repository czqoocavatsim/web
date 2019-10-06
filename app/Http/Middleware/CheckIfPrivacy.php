<?php

namespace App\Http\Middleware;

use Closure;

class CheckIfPrivacy
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
        if (Auth::check()) {
            if (Auth::user()->init == 0) {
                return $next($request);
            }
        }

        return ('/')->with('error', 'Please accept the CZQO privacy policy.');
    }
}
