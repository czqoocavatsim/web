<?php

namespace App\Http\Controllers;

use App\CarouselItem;
use App\Ticket;
use Illuminate\Http\Request;
use App\News;
use Auth;

class HomeController extends Controller
{
    public function view()
    {
        //VATSIM online controllers
        $logFile = __DIR__.'/vendor/skymeyer/vatsimphp/app/logs/pilots.log';
        $vatsim = new \Vatsimphp\VatsimData();
        $vatsim->setConfig('cacheOnly', false);
        $vatsim->setConfig('logFile', $logFile);
        $ganderControllers = [];
        $shanwickControllers = [];
        if ($vatsim->loadData()) {
            $ganderControllers = $vatsim->searchCallsign('CZQX_');
            $shanwickControllers = $vatsim->searchCallsign('EGGX_');
        }

        //News
        $news = News::where('type', '!=', 'Certification')->take(5)->get()->sortByDesc('id');
        $promotions = News::where('type', 'Certification')->take(5)->get()->sortByDesc('id');
        $carouselItems = CarouselItem::all();
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        $vatcanNews = file_get_contents('http://www.vatcan.ca/ajax/news', false, stream_context_create($arrContextOptions));
        $vatcanNewsJsonFull = \GuzzleHttp\json_decode($vatcanNews, true);
        $vatcanNewsJson = array_splice($vatcanNewsJsonFull, 5);
        return view('home', compact('ganderControllers', 'shanwickControllers', 'news', 'vatcanNewsJson', 'promotions', 'carouselItems'));
    }

}
