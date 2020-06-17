<?php

namespace App\Http\Controllers;

use App\Models\Events\Event;
use App\Models\News\HomeNewControllerCert;
use App\Models\News\News;
use App\Models\Publications\AtcResource;
use App\Models\Settings\RotationImage;
use App\Models\Tickets\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $certifications = HomeNewControllerCert::all()->sortByDesc('timestamp')->take(6);

        //Next event
        $nextEvent = Event::where('start_timestamp', '>', Carbon::now())->get()->sortByDesc('id')->first();

        return view('index', compact('ganderControllers', 'shanwickControllers', 'news', 'certifications', 'nextEvent'));
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
        $certification = 'not_certified';
        $active = true;
        /*$potentialRosterMember = RosterMember::where('user_id', $user->id)->first();
        if ($potentialRosterMember === null) {
            $certification = 'not_certified';
            $active = 2;
        } else {
            $certification = $potentialRosterMember->status;
            $active = $potentialRosterMember->active;
        }*/
        $openTickets = Ticket::where('user_id', $user->id)->where('status', 0)->get();

        $atcResources = AtcResource::all()->sortBy('title');

        $bannerImg = RotationImage::all()->random();

        if ($user->preferences->enable_beta_features) {
            return view('dashboard.indexnew', compact('openTickets', 'certification', 'active', 'atcResources', 'bannerImg'));
        } else {
            return view('dashboard.index', compact('openTickets', 'certification', 'active', 'atcResources', 'bannerImg'));
        }
    }
}
