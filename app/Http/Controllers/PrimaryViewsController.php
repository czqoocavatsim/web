<?php

namespace App\Http\Controllers;

use App\Models\Events\Event;
use App\Models\News\HomeNewControllerCert;
use App\Models\News\News;
use App\Models\Publications\AtcResource;
use App\Models\Roster\RosterMember;
use App\Models\Settings\RotationImage;
use App\Models\Tickets\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PrimaryViewsController extends Controller
{
    /* Home page */
    public function home()
    {
        //VATSIM online controllers
        $vatsim = new \Vatsimphp\VatsimData();
        $vatsim->setConfig('cacheOnly', false);
        $ganderControllers = [];
        $shanwickControllers = [];
        if ($vatsim->loadData()) {
            $ganderControllers = $vatsim->searchCallsign('CZQX_');
            $shanwickControllers = $vatsim->searchCallsign('EGGX_');
        }

        //News
        $news = News::where('visible', true)->get()->sortByDesc('published')->take(3);
        $certifications = HomeNewControllerCert::all()->sortByDesc('timestamp')->take(4);

        //Next event
        $nextEvent = Event::where('start_timestamp', '>', Carbon::now())->get()->sortByDesc('id')->first();

        //Top controllers
        $topControllers = RosterMember::where('monthly_hours', '>', 0)->sortByDesc('monthly_hours')->take(6);

        return view('index', compact('ganderControllers', 'shanwickControllers', 'news', 'certifications', 'nextEvent', 'topControllers'));
    }

    /*
    Big map /map
    */
    public function map()
    {
        //VATSIM online controllers
        $vatsim = new \Vatsimphp\VatsimData();
        $vatsim->setConfig('cacheOnly', false);
        $ganderControllers = [];
        $shanwickControllers = [];
        $planes = null;
        if ($vatsim->loadData()) {
            $ganderControllers = $vatsim->searchCallsign('CZQX_');
            $shanwickControllers = $vatsim->searchCallsign('EGGX_');
            $planes = $vatsim->getPilots()->toArray();
        }
        return view('map', compact('ganderControllers', 'shanwickControllers', 'planes'));
    }

    /*
    Dashboard
    */
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        $openTickets = Ticket::where('user_id', $user->id)->where('status', 0)->get();

        $atcResources = AtcResource::all()->sortBy('title');

        $bannerImg = RotationImage::all()->random();

        //Quote of the day
        $quote = Cache::remember('quoteoftheday', 86400, function () {
            //Download via CURL
            $url = 'https://quotes.rest/qod';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);
            return json_decode($output);
        });

        if ($user->preferences->enable_beta_features) {
            return view('dashboard.indexnew', compact('openTickets', 'atcResources', 'bannerImg'));
        } else {
            return view('dashboard.index', compact('openTickets', 'atcResources', 'bannerImg', 'quote'));
        }
    }
}
