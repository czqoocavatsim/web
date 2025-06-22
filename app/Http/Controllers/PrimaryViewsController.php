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
use App\Models\Statistics\PilotStats;
use App\Models\Statistics\AircraftStats;
use App\Models\Statistics\ControllerStats;
use App\Models\Statistics\AirlineStats;
use App\Models\Statistics\AirportPairStats;
use App\Models\Network\CTPDates;
use App\Models\Network\FIRInfo;
use App\Models\Network\FIRPilots;
use App\Services\VATSIMClient;

class PrimaryViewsController extends Controller
{
    /* Home page */
    public function home(Request $request)
    {
        //VATSIM online controllers
        $controllers = SessionLog::whereNull('session_end')->orderBy('session_start', 'asc')->get();

        //News
        $news = News::where('visible', true)->get()->sortByDesc('published')->first();

        // Statistics
        $certifications = HomeNewControllerCert::all()->sortByDesc('timestamp')->take(5);
        $topPilot = PilotStats::where('current', '>', 0)->orderByDesc('current')->take(5)->get();
        $yearAircraft = AircraftStats::where('year', '>', 0)->orderByDesc('year')->take(5)->get();
        $topControllers = ControllerStats::where('current', '>', 0)->orderByDesc('current')->take(5)->get();
        $yearAirlines = AirlineStats::where('year', '>', 0)->orderByDesc('year')->take(5)->get();
        $topPairAirports  = AirportPairStats::where('current', '>', 0)->orderByDesc('current')->take(5)->get();
        $yearControllers = ControllerStats::where('year', '>', 0)->orderByDesc('year')->take(5)->get();

        //Next event
        $nextEvent = Event::where('start_timestamp', '>', Carbon::now())->get()->sortBy('start_timestamp')->first();

        //CTP Mode?
        $ctpEvents = CTPDates::whereBetween('oca_end', [Carbon::now(),Carbon::now()->addDays(60)])->first();
        $ctpAircraft = FIRInfo::all()->first();

        $ctpMode = false;

        if($ctpEvents){
            if (Carbon::now()->between($ctpEvents->oca_start, $ctpEvents->oca_end)) {
                $ctpMode = 2;
            } elseif($ctpEvents && Carbon::now() < $ctpEvents->oca_end) {
                $ctpMode = 1;
            }
        }

        return view('index', compact('controllers', 'news', 'certifications', 'topPilot', 'yearControllers', 'yearAircraft', 'topControllers', 'yearAirlines', 'topPairAirports', 'nextEvent', 'ctpEvents', 'ctpAircraft', 'ctpMode'));
    }

    // General Homepage API Update
    public function homeUpdate()
    {
        $ctpAircraft = FIRInfo::all()->first();

        return response()->json([
            'czqo' => $ctpAircraft->czqo,
            'eggx' => $ctpAircraft->eggx,
            'ganwick' => $ctpAircraft->ganwick,
            'kzny' => $ctpAircraft->kzny,
            'lppo' => $ctpAircraft->lppo,
            'bird' => $ctpAircraft->bird,
        ]);
    }

    // Controller API Update
    public function updateControllers($status)
    {
        $controllers = SessionLog::whereNull('session_end')->orderBy('session_start', 'asc')->get();

        if($status == 1){
            return view('partials.homepage.general-controllers', compact('controllers'))->render();
        }
        // CTP Controller Rendering
        if($status == 2){
            return view('partials.homepage.ctp-controllers', compact('controllers'))->render();
        }
    }

    /*
    Big map /map
    */
    public function map()
    {
        //VATSIM online controllers
        $czqoOnline = SessionLog::whereNull('session_end')->where('callsign', 'like', 'CZQO%')->exists();
        $eggxOnline = SessionLog::whereNull('session_end')->where('callsign', 'like', 'EGGX%')->exists();
        $natOnline = SessionLog::whereNull('session_end')->where('callsign', 'like', 'NAT%')->exists();
        $nycOnline = SessionLog::whereNull('session_end')->where('callsign', 'like', 'NY%')->exists();

        $vatsim = new VATSIMClient();
        $planes = $vatsim->getPilots();

        // Scower the VATSIM Datafeed and check callsigns
        $bird = $vatsim->searchCallsign("BIRD_CTR", false); if($bird) $bird=1; else $bird=0;
        $egpx = $vatsim->searchCallsign("SCO_CTR", false); if($egpx) $egpx=1; else $egpx=0;
        $eisn = $vatsim->searchCallsign("EISN_CTR", false); if($eisn) $eisn=1; else $eisn=0;
        $lfrr = $vatsim->searchCallsign("LFRR_CTR", false); if($lfrr) $lfrr=1; else $lfrr=0;
        $lecm = $vatsim->searchCallsign("LECM_CTR", false); if($lecm) $lecm=1; else $lecm=0;
        $lppo = $vatsim->searchCallsign("LPPO_FSS", false); if($lppo) $lppo=1; else $lppo=0;
        $ttzo = $vatsim->searchCallsign("TTZO_FSS", false); if($ttzo) $ttzo=1; else $ttzo=0;
        $ttzp = $vatsim->searchCallsign("TTZP_CTR", false); if($ttzp) $ttzp=1; else $ttzp=0;
        $tjzs = $vatsim->searchCallsign("SJU_CTR", false); if($tjzs) $tjzs=1; else $tjzs=0;
        $kzmo = $vatsim->searchCallsign("ZMO_CTR", false); if($kzmo) $kzmo=1; else $kzmo=0;
        $kzma = $vatsim->searchCallsign("MIA_CTR", false); if($kzma) $kzma=1; else $kzma=0;
        $kzjx = $vatsim->searchCallsign("ZJX_CTR", false); if($kzmo) $kzmo=1; else $kzmo=0;
        $dc = $vatsim->searchCallsign("DC_CTR", false); if($dc) $dc=1; else $dc=0;
        $kzbw = $vatsim->searchCallsign("BOS_CTR", false); if($kzbw) $kzbw=1; else $kzbw=0;
        $czqm = $vatsim->searchCallsign("CZQM_CTR", false); if($czqm) $czqm=1; else $czqm=0;
        $czqx = $vatsim->searchCallsign("CZQX_CTR", false); if($czqx) $czqx=1; else $czqx=0;
        $czul = $vatsim->searchCallsign("MTL_CTR", false); if($czul) $czul=1; else $czul=0;

        $ControllerOnline = [
            'eggx' => $eggxOnline,
            'czqo' => $czqoOnline,
            'nat' => $natOnline,
            'nyc' => $nycOnline,
            'bird' => $bird,
            'egpx' => $egpx,
            'eisn' => $eisn,
            'lfrr' => $lfrr,
            'lecm' => $lecm,
            'lppo' => $lppo,
            'ttzo' => $ttzo,
            'ttzp' => $ttzp,
            'tjzs' => $tjzs,
            'kzmo' => $kzmo,
            'kzma' => $kzma,
            'kzjx' => $kzjx,
            'kzdc' => $dc,
            'kzbw' => $kzbw,
            'czqm' => $czqm,
            'czqx' => $czqx,
            'czul' => $czul,
        ];

        return view('pilots.map', compact('planes', 'ControllerOnline'));
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
