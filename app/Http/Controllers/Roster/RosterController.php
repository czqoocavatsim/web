<?php

namespace App\Http\Controllers\Roster;

use App\Http\Controllers\Controller;
use App\Models\Roster\RosterMember;
use Illuminate\Http\Request;

class RosterController extends Controller
{
    public function publicRoster()
    {
        //Get roster
        $roster = RosterMember::where('certification', '!=', 'not_certified')->get();

        //Return view
        return view('roster', compact('roster'));
    }

    public function admin()
    {
        //Get the roster
        $roster = RosterMember::all();

        //Return the view
        return view('admin.training.roster.index', compact('roster'));
    }
}
