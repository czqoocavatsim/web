<?php

namespace App\Http\Controllers;

use Throwable;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\News\News;
use Vatsimphp\VatsimData;
use App\Models\Events\Event;
use Illuminate\Http\Request;
use App\Models\Roster\RosterMember;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Thujohn\Twitter\Facades\Twitter;
use Illuminate\Support\Facades\Cache;
use App\Models\Settings\RotationImage;
use App\Models\Publications\AtcResource;
use App\Models\News\HomeNewControllerCert;

class PrimaryViewsController extends Controller
{
    /* Home page */
    public function home(Request $request)
    {
        //VATSIM online controllers
        $vatsimData = new VatsimData();
        $dataLoaded = $vatsimData->loadData();

        $ganderControllers = [];
        $shanwickControllers = [];
        $controllers = [];
        if ($dataLoaded) {
            $ganderControllers = $vatsimData->searchCallsign('CZQO_');
            $shanwickControllers = $vatsimData->searchCallsign('EGGX_');
            $natControllers = $vatsimData->searchCallsign('NAT_');
            $controllers = array_merge($ganderControllers->toArray(), $shanwickControllers->toArray(), $natControllers->toArray());
        } else {
            Log::error('PrimaryViewsController home: VATSIMPhp failed to load data');
        }

        //News
        $news = News::where('visible', true)->get()->sortByDesc('published')->first();
        $certifications = HomeNewControllerCert::all()->sortByDesc('timestamp')->take(3);

        //Next event
        $nextEvent = Event::where('start_timestamp', '>', Carbon::now())->get()->sortBy('start_timestamp')->first();

        //Top controllers
        $topControllers = RosterMember::where('monthly_hours', '>', 0)->get()->sortByDesc('monthly_hours')->take(3);

        //Twitter
        $tweets = Cache::remember('twitter.timeline', 86400, function () {
            try {
                return Twitter::getUserTimeline(['screen_name' => 'ganderocavatsim', 'count' => 2, 'format' => 'array']);
            } catch (Throwable $ex) {
                return null;
            }
        });

        //CTP Mode?
        $ctpMode = false;
        if (config('app.ctp_home_page')) {
            $ctpMode = true;
        }

        return view('index', compact('controllers', 'news', 'certifications', 'nextEvent', 'topControllers', 'tweets', 'ctpMode'));
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
        $controllerOnline = false;
        $planes = null;
        if ($vatsim->loadData()) {
            $ganderControllers = $vatsim->searchCallsign('CZQX_');
            $shanwickControllers = $vatsim->searchCallsign('EGGX_');
            $controllers = array_merge($ganderControllers->toArray(), $shanwickControllers->toArray());
            if (count($controllers) > 1) {
                $controllerOnline = true;
            }
            $planes = $vatsim->getPilots()->toArray();
        }

        return view('pilots.map', compact('planes', 'controllerOnline'));
    }

    /*
    Dashboard
    */
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        $atcResources = AtcResource::all()->sortBy('title');

        $bannerCollection = RotationImage::all();
        if (!$bannerCollection->isEmpty()) {
            $bannerImg = $bannerCollection->random();
        } else {
            $bannerImg = null;
        }

        //Quote of the day
        $quote = Cache::remember('quoteoftheday', 86400, function () {
            $client = new Client();
            $headers = [
                'Authorization' => 'Bearer '.env('QUOTES_API_TOKEN')
            ];
            $output = $client->get('https://quotes.rest/qod',[
                'headers' => $headers
            ]);

            return json_decode($output->getBody());
        });

        return view('my.index', compact('atcResources', 'bannerImg', 'quote'));
    }
}
