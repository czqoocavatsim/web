<?php

namespace App\Http\Controllers;

use App\RosterMember;
use Illuminate\Http\Request;
use App\Ticket;
use Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $certification = null;
        $active = null;
        $potentialRosterMember = RosterMember::where('user_id', $user->id)->first();
        if ($potentialRosterMember === null)
        {
            $certification = "not_certified";
            $active = 2;
        }
        else
        {
            $certification = $potentialRosterMember->status;
            $active = $potentialRosterMember->active;
        }
        $openTickets = Ticket::where('user_id', $user->id)->where('status', 0)->get();
        return view('dashboard.index', compact('openTickets', 'certification', 'active'));
    }
}
