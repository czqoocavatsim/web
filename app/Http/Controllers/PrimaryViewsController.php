<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\News\News;
use App\Models\Events\Event;
use Illuminate\Http\Request;
use App\Models\Network\SessionLog;
use App\Models\Network\ExternalController;
use App\Models\Roster\RosterMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Settings\RotationImage;
use App\Models\Publications\AtcResource;
use App\Models\News\HomeNewControllerCert;
use App\Models\Network\CTPDates;
use App\Models\Network\FIRInfo;
use App\Services\VATSIMClient;

class PrimaryViewsController extends Controller
{
    /* Home page */
    public function home(Request $request)
    {
        //VATSIM online controllers
        $controllers = SessionLog::whereNull('session_end')->orderBy('session_start', 'asc')->get();

        // Controller Certifications
        $externalController = ExternalController::all();
        $ganderController = RosterMember::all();

        //News
        $news = News::where('visible', true)->get()->sortByDesc('published')->first();
        $certifications = HomeNewControllerCert::all()->sortByDesc('timestamp')->take(7);

        //Next event
        $nextEvent = Event::where('start_timestamp', '>', Carbon::now())->get()->sortBy('start_timestamp')->first();

        //Top Month Controllers
        $rosterMembers = RosterMember::where('monthly_hours', '>', 0)->get();
        $externalControllers = ExternalController::where('monthly_hours', '>', 0)->get();
        $topControllers = $rosterMembers->merge($externalControllers)->sortByDesc('monthly_hours')->take(7);

        //Top controllers
        $rosterMembers = RosterMember::where('currency', '>', 0)->get();
        $externalControllers = ExternalController::where('currency', '>', 0)->get();
        $yearControllers = $rosterMembers->merge($externalControllers)->sortByDesc('currency')->take(7);

        //CTP Mode?
        $ctpEvents = CTPDates::whereMonth('oca_start', Carbon::now()->month)->whereYear('oca_start', Carbon::now()->year)->first();
        $ctpAircraft = FIRInfo::all()->first();

        $ctpMode = false;

        if($ctpEvents){
            if (Carbon::now()->between($ctpEvents->oca_start, $ctpEvents->oca_end)) {
                $ctpMode = 2;
            } elseif($ctpEvents && Carbon::now() < $ctpEvents->oca_end) {
                $ctpMode = 1;
            }
        }

        return view('index', compact('controllers', 'news', 'certifications', 'nextEvent', 'topControllers', 'yearControllers', 'ctpMode', 'ctpEvents', 'ctpAircraft'));
    }

    /*
    Big map /map
    */
    public function map()
    {
        //VATSIM online controllers
        $controllerOnline = SessionLog::whereNull('session_end')->exists();

        $vatsim = new VATSIMClient();
        $planes = $vatsim->getPilots();

        return view('pilots.map', compact('planes', 'controllerOnline'));
    }

    /*
    Dashboard
    */
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        $sessions = SessionLog::where('cid', Auth::user()->id)->where('created_at', '>=', Carbon::now()->startOfYear())->orderBy('created_at', 'desc')->get();

        $externalController = ExternalController::find(Auth::user()->id);

        $atcResources = AtcResource::all()->sortBy('title');

        $bannerCollection = RotationImage::all();

        // return $bannerCollection;

        if (!$bannerCollection->isEmpty()) {
            $bannerImg = $bannerCollection->random();
        } else {
            $bannerImg = null;
        }

        return view('my.index', compact('atcResources', 'externalController', 'bannerImg', 'sessions'));
    }
}
