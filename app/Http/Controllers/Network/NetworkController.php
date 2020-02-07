<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\Network\MonitoredPosition;
use App\Models\Network\SessionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NetworkController extends Controller
{
    public function index()
    {
        return view('dashboard.network.index');
    }

    public function monitoredPositionsIndex()
    {
        $positions = MonitoredPosition::all()->sortByDesc('identifier');
        return view('dashboard.network.monitoredpositions.index', compact('positions'));
    }

    public function viewMonitoredPosition($position)
    {
        $position = MonitoredPosition::where(strtolower('identifier'), strtolower($position))->firstOrFail();
        return view('dashboard.network.monitoredpositions.view', compact('position'));
    }

    public function createMonitoredPosition(Request $request)
    {
        $messages = [
            'identifier.required' => 'Please type an identifier prefix/callsign.'
        ];

        $validator = Validator::make($request->all(), [
            'identifier' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator, 'createMonitoredPosition');
        }

        $position = new MonitoredPosition();
        $position->identifier = $request->get('identifier');
        $position->staffOnly = $request->get('staffOnly') == 'yes' ? true : false;
        $position->save();

        return redirect()->route('network.monitoredpositions.view', strtolower($position->identifier));
    }
}
