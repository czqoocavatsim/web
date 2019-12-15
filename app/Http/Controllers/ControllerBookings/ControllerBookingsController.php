<?php

namespace App\Http\Controllers\ControllerBookings;

use App\Http\Controllers\Controller;
use App\Models\ControllerBookings\ControllerBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ControllerBookingsController extends Controller
{
    public function indexPublic()
    {
        $bookings = ControllerBooking::all()->sortBy('start_time');
        $upcomingBookings = [];
        $iterations = 0;
        foreach ($bookings as $b) {
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

