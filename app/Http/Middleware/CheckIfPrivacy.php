<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

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
            if (!Auth::user()->init) {
                if (Request::is('me/accept-privacy-policy') || Request::is('privacydeny') || Request::is('privacyaccept')) {
                    return $next($request);
                } else {
                    return redirect()->route('accept-privacy-policy')->with('info', 'Please accept the Privacy Policy');
                }
            } else {
                return $next($request);
            }
        //otherwise
        } else {
            return $next($request);
        }
    }
}
