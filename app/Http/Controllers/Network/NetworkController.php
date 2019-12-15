<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\Network\NetworkLog;
use App\Models\Network\VatsimPosition;
use Illuminate\Http\Request;

class NetworkController extends Controller
{
    public function networkActivityIndex()
    {
        abort(403, 'Havent done this shit yet');
    }

    public function positionsIndex()
    {
        $positions = VatsimPosition::all();

        return view('dashboard.network.positions.index', compact('positions'));
    }

    public function viewPosition($id)
    {
        $position = VatsimPosition::whereId($id)->firstOrFail();

        return view('dashboard.network.positions.view', compact('position'));
    }

    public function addPosition(Request $request)
    {
        $this->validate($request, [
            'callsign' => 'required|max:10',
            'type' => 'sometimes|min:3|max:20',
        ]);

        //Does the position already exist?
        if (VatsimPosition::where('callsign', $request->get('callsign'))->first()) {
            return redirect()->back()->withInput()->with('error', 'Callsign already used');
        }

        //Create a position
        $position = new VatsimPosition();
        $position->callsign = strtoupper($request->get('callsign'));
        $position->type = $request->get('type');
        if ($request->get('staff_only') == 'yes') {
            $position->staff_only = 1;
        } else {
            $position->staff_only = 0;
        }
        $position->save();

        //Redirect
        return redirect()->route('network.positions.view', $position->id)->with('success', 'Position created!');
    }

    public function removePosition($id)
    {
        //Get the position
        $position = VatsimPosition::whereId($id)->firstOrFail();
        //Delete it
        $position->delete();
        //Redirect
        return redirect()->route('network.positions.index', $position->id)->with('info', 'Position deleted!');
    }

    public function editPosition(Request $request, $id)
    {
        $this->validate($request, [
            'callsign' => 'sometimes|max:10',
            'type' => 'sometimes|min:3|max:20',
        ]);

        //Does the position already exist?
        if (VatsimPosition::whereId($id)->first()) {
            abort(400, 'Position not found');
        }

        //Create a position
        $position = VatsimPosition::whereId($id)->firstOrFail();
        $position->callsign = strtoupper($request->get('callsign'));
        $position->type = $request->get('type');
        if ($request->get('staff_only') == 'yes') {
            $position->staff_only = 1;
        } else {
            $position->staff_only = 0;
        }
        $position->save();

        //Redirect
        return redirect()->route('network.positions.view', $position->id)->with('success', 'Position edited!');
    }

    public function logIndex()
    {
        $log = NetworkLog::all();

        return view('dashboard.network.log.index', compact('log'));
    }
}
