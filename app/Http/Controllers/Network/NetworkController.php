<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\Network\MonitoredPosition;
use App\Models\Network\SessionLog;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NetworkController extends Controller
{
    public function index()
    {
        //Create hours per day array
        $hoursPerDay = [];

        //Create carbon period
        $timePeriod = CarbonPeriod::create(Carbon::now()->subDays(30), Carbon::now());

        //For each day...
        foreach ($timePeriod as $day) {
            //Get sessions for that day
            $sessionsForDay = SessionLog::whereDate('session_start', $day)->select('session_start', 'duration')->orderBy('session_start')->get();

            //Calculate total hours that day
            $hoursThisDay = 0;
            foreach ($sessionsForDay as $session) {
                $hoursThisDay += $session->duration;
            }

            //Push to array
            array_push($hoursPerDay, $hoursThisDay);
        }

        //Return view
        return view('admin.network.index', compact('hoursPerDay'));
    }

    public function monitoredPositionsIndex()
    {
        $positions = MonitoredPosition::all()->sortByDesc('identifier');

        return view('admin.network.monitoredpositions.index', compact('positions'));
    }

    public function viewMonitoredPosition($position)
    {
        $position = MonitoredPosition::where(strtolower('identifier'), strtolower($position))->firstOrFail();

        return view('admin.network.monitoredpositions.view', compact('position'));
    }

    public function createMonitoredPosition(Request $request)
    {
        $messages = [
            'identifier.required' => 'Please type an identifier prefix/callsign.',
        ];

        $validator = Validator::make($request->all(), [
            'identifier' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator, 'createMonitoredPosition');
        }

        $position = new MonitoredPosition();
        $position->identifier = $request->get('identifier');
        $position->staff_only = false; //$request->get('staffOnly') == 'yes' ? true : false;
        $position->save();

        return redirect()->route('network.monitoredpositions.view', strtolower($position->identifier));
    }
}
