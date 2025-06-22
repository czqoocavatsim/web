<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\Network\MonitoredPosition;
use App\Models\Network\SessionLog;
use App\Models\Network\ExternalController;
use App\Models\Statistics\ControllerStats;
use App\Models\Statistics\PilotStats;
use App\Models\Roster\RosterMember;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StatisticsController extends Controller
{
    public function index()
    {
        ### CONTROLLER STATISTICS
        $topControllers = ControllerStats::where('current', '>', 0)->orderByDesc('current')->take(5)->get();
        $lastControllers = ControllerStats::where('last_month', '>', 0)->orderByDesc('last_month')->take(5)->get();
        $yearControllers = ControllerStats::where('year', '>', 0)->orderByDesc('year')->take(5)->get();

        ### PILOT STATISTICS
        $topPilot = PilotStats::where('current', '>', 0)->orderByDesc('current')->take(5)->get();
        $lastPilot = PilotStats::where('last_month', '>', 0)->orderByDesc('last_month')->take(5)->get();
        $yearPilot = PilotStats::where('year', '>', 0)->orderByDesc('year')->take(5)->get();

        return view('stats.index', compact('topControllers', 'lastControllers', 'yearControllers', 'topPilot', 'lastPilot', 'yearPilot'));
    }
}
