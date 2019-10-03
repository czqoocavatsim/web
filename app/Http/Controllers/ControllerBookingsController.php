<?php

namespace App\Http\Controllers;

use App\ControllerBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ControllerBookingsController extends Controller
{
    public function index()
    {
        $bookings = ControllerBooking::all()->sortBy('start_time');
        $upcomingBookings = [];
        $iterations = 0;
        foreach ($bookings as $b)
        {
            $iterations++;
            if ($iterations > 10) {
                exit;
            }
            $start = Carbon::parse($b->start_time);
            if ($start->isFuture()) {
                array_push($upcomingBookings, $b);
            }
        }
        return view('controllerbookings', compact('bookings', 'upcomingBookings'));
    }
}
