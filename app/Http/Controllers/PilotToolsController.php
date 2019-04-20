<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PilotToolsController extends Controller
{
    public function generateOceanicClearance(Request $request)
    {
        $this->validate($request, [
            'callsign' => 'required',
            'flightLevel' => 'required',
            'mach' => 'required',
            'entry' => 'required',
            'time' => 'required',
            'tmi' => 'required'
        ]);

        //Check if either NAT or route is filled
        

        return redirect()->route('pilots.oceanic-clearance')->with('success', 'Clearance generated!');
    }
}
