<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\Network\MonitoredPosition;
use App\Models\Network\SessionLog;
use App\Models\Network\ExternalController;

use App\Models\Statistics\ControllerStats;
use App\Models\Statistics\PilotStats;
use App\Models\Statistics\AirlineStats;
use App\Models\Statistics\AircraftStats;
use App\Models\Statistics\LevelStats;
use App\Models\Statistics\AirportStats;
use App\Models\Statistics\AirportPairStats;

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

        ### AIRCRAFT STATISTICS
        $topAirlines  = AirlineStats::where('current', '>', 0)->orderByDesc('current')->take(5)->get();
        $lastAirlines = AirlineStats::where('last_month', '>', 0)->orderByDesc('last_month')->take(5)->get();
        $yearAirlines = AirlineStats::where('year', '>', 0)->orderByDesc('year')->take(5)->get();

        $topAircraft  = AircraftStats::where('current', '>', 0)->orderByDesc('current')->take(5)->get();
        $lastAircraft = AircraftStats::where('last_month', '>', 0)->orderByDesc('last_month')->take(5)->get();
        $yearAircraft = AircraftStats::where('year', '>', 0)->orderByDesc('year')->take(5)->get();

        $topLevels  = LevelStats::where('current', '>', 0)->orderByDesc('current')->take(5)->get();
        $lastLevels = LevelStats::where('last_month', '>', 0)->orderByDesc('last_month')->take(5)->get();
        $yearLevels = LevelStats::where('year', '>', 0)->orderByDesc('year')->take(5)->get();

        ### AIRPORT STATISTICS
        $topDepAirports  = AirportStats::where('current_dep', '>', 0)->orderByDesc('current_dep')->take(5)->get();
        $lastDepAirports = AirportStats::where('last_month_dep', '>', 0)->orderByDesc('last_month_dep')->take(5)->get();
        $yearDepAirports = AirportStats::where('year_dep', '>', 0)->orderByDesc('year_dep')->take(5)->get();
        $topArrAirports  = AirportStats::where('current_arr', '>', 0)->orderByDesc('current_arr')->take(5)->get();
        $lastArrAirports = AirportStats::where('last_month_arr', '>', 0)->orderByDesc('last_month_arr')->take(5)->get();
        $yearArrAirports = AirportStats::where('year_arr', '>', 0)->orderByDesc('year_arr')->take(5)->get();

        $topPairAirports  = AirportPairStats::where('current', '>', 0)->orderByDesc('current')->take(5)->get();
        $lastPairAirports = AirportPairStats::where('last_month', '>', 0)->orderByDesc('last_month')->take(5)->get();
        $yearPairAirports = AirportPairStats::where('year', '>', 0)->orderByDesc('year')->take(5)->get();

        return view('stats.index', compact(
            'topControllers', 'lastControllers', 'yearControllers', 'topPilot', 'lastPilot', 'yearPilot',
            'topAirlines', 'lastAirlines', 'yearAirlines', 'topAircraft', 'lastAircraft', 'yearAircraft',
            'topLevels', 'lastLevels', 'yearLevels', 'topDepAirports', 'lastDepAirports', 'yearDepAirports',
            'topArrAirports', 'lastArrAirports', 'yearArrAirports', 'topPairAirports', 'lastPairAirports', 'yearPairAirports'));
    }
}
