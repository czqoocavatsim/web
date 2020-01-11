<?php

namespace App\Http\Controllers;

use App\Models\News\News;
use App\Models\News\CarouselItem;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function view()
    {
        //VATSIM online controllers
        $vatsim = Cache::remember('vatsim.data', 900, function () {
            $logFile = __DIR__.'/vendor/skymeyer/vatsimphp/app/logs/pilots.log';
            $data = new \Vatsimphp\VatsimData();
            $data->setConfig('cacheOnly', true);
            $data->setConfig('logFile', $logFile);
            return $data;
        });
        $ganderControllers = [];
        $shanwickControllers = [];
        $planes = [];
        if ($vatsim->loadData()) {
            $ganderControllers = $vatsim->searchCallsign('M');
            Log::info($ganderControllers->toArray());
            $shanwickControllers = $vatsim->searchCallsign('M');
            $planes = $vatsim->getPilots()->toArray();
        }


        //News
        $news = News::all()->sortByDesc('published')->take(3);
        $promotions = News::where('certification', true)->get()->take(6);
        $carouselItems = CarouselItem::all();

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
        return view('index', compact('ganderControllers', 'shanwickControllers', 'news', 'vatcanNews', 'promotions', 'carouselItems', 'planes'));
    }
}
