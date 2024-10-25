<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\News\News;
use App\Models\Events\Event;
use Illuminate\Http\Request;
use App\Models\Network\SessionLog;
use App\Models\Roster\RosterMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Settings\RotationImage;
use App\Models\Publications\AtcResource;
use App\Models\News\HomeNewControllerCert;
use App\Services\VATSIMClient;

class PrimaryViewsController extends Controller
{
    /* Home page */
    public function home(Request $request)
    {
        //VATSIM online controllers
        $controllers = SessionLog::whereNull('session_end')->get();

        //News
        $news = News::where('visible', true)->get()->sortByDesc('published')->first();
        $certifications = HomeNewControllerCert::all()->sortByDesc('timestamp')->take(3);

        //Next event
        $nextEvent = Event::where('start_timestamp', '>', Carbon::now())->get()->sortBy('start_timestamp')->first();

        //Top controllers
        $topControllers = RosterMember::where('monthly_hours', '>', 0)->get()->sortByDesc('monthly_hours')->take(3);

        //CTP Mode?
        $ctpMode = false;
        if (config('app.ctp_home_page')) {
            $ctpMode = true;
        }

        return view('index', compact('controllers', 'news', 'certifications', 'nextEvent', 'topControllers', 'ctpMode'));
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

        $atcResources = AtcResource::all()->sortBy('title');

        $bannerCollection = RotationImage::all();
        if (!$bannerCollection->isEmpty()) {
            $bannerImg = $bannerCollection->random();
        } else {
            $bannerImg = null;
        }

        return view('my.index', compact('atcResources', 'bannerImg', 'sessions'));
    }
}
