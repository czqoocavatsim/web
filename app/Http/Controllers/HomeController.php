<?php

namespace App\Http\Controllers;

use App\Models\Events\Event;
use App\Models\News\News;
use App\Models\News\CarouselItem;
use App\Models\News\HomeNewControllerCert;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function view()
    {
        //VATSIM online controllers
        $vatsim = new \Vatsimphp\VatsimData();
        $vatsim->setConfig('cacheOnly', false);
        $ganderControllers = [];
        $shanwickControllers = [];
        $planes = null;
        if ($vatsim->loadData()) {
            $ganderControllers = $vatsim->searchCallsign('CZQX_');
            Log::info($ganderControllers->toArray());
            Log::info('cheese');
            $shanwickControllers = $vatsim->searchCallsign('EGGX_');
            $planes = $vatsim->getPilots()->toArray();
        }

        //News
        $news = News::where('visible', true)->get()->sortByDesc('published')->take(3);
        $certifications = HomeNewControllerCert::all()->sortByDesc('timestamp')->take(6);
        $carouselItems = CarouselItem::all();

        //Event
        $nextEvent = Event::where('start_timestamp', '>', Carbon::now())->get()->sortByDesc('id')->first();

        //Get VATCAN news
        $vatcanNews = Cache::remember('news.vatcan', 21600, function () {
            $url = 'http://www.vatcan.ca/ajax/news';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $json = curl_exec($ch);
            error_log('Grabbing VATCAN news from API');
            Log::info('Grabbing VATCAN news from API '.date('Y-m-d H:i:s'));
            curl_close($ch);
            return json_decode($json);
        });
        return view('index', compact('ganderControllers', 'shanwickControllers', 'news', 'vatcanNews', 'certifications', 'carouselItems', 'planes', 'nextEvent'));
    }

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
}
