<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\Network\MonitoredPosition;
use App\Models\Network\SessionLog;
use App\Models\Network\ExternalController;
use App\Models\Network\ControllerStats;
use App\Models\Roster\RosterMember;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StatisticsController extends Controller
{
    public function index()
    {
        //Top Month Controllers
        $rosterMembers = RosterMember::where('monthly_hours', '>', 0)->get();
        $externalControllers = ExternalController::where('monthly_hours', '>', 0)->get();
        $topControllers = $rosterMembers->merge($externalControllers)->sortByDesc('monthly_hours')->take(5);

        //Top controllers
        $rosterMembers = RosterMember::where('currency', '>', 0)->get();
        $externalControllers = ExternalController::where('currency', '>', 0)->get();
        $yearControllers = $rosterMembers->merge($externalControllers)->sortByDesc('currency')->take(5);

        $lastControllers = ControllerStats::all()->sortByDesc('monthly_hours')->take(5);

        // return $lastControllers;

        return view('stats.index', compact('topControllers', 'lastControllers', 'yearControllers'));
    }
}
