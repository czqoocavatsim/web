<?php

namespace App\Http\Middleware;

use App\ControllerBookingsBan;
use Auth;
use Closure;

class BookingBanMiddleware
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
            if (! Auth::user()->bookingBanned()) {
                return $next($request);
            }
            $ban = ControllerBookingsBan::where('user_id', Auth::id())->firstOrFail();
            abort(403, 'You have been banned from using the CZQO booking system for: '.$ban->reason.'. If you wish to dispute this, please email the FIR Chief.');
        }

        return redirect('/');
    }
}
