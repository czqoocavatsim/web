<?php

namespace App\Jobs;

use App\Models\Users\User;
use App\Models\Network\MonitoredPosition;
use App\Models\Network\SessionLog;
use App\Models\Network\ExternalController;
use App\Models\Network\CTPDates;
use App\Models\Roster\RosterMember;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Network\FIRInfo;
use App\Models\Network\FIRPilots;
use App\Models\Network\FIRAircraft;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\VATSIMClient;
use App\Services\DiscordClient;

class ProcessSessionLogging implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $ctp_events = CTPDates::where('oca_start', '<', Carbon::now())->where('oca_end', '>', Carbon::now())->first();

        //BEGIN CONTROLLER SESSION CHECK
        //Get All Time Monitored Positions
        $mainPositions = MonitoredPosition::where('ctp_only', null)->get();

        if($ctp_events){
            // LPPO Positions
            if($ctp_events->lppo_coverage == 1){
                $lppoPositions = MonitoredPosition::where('ctp_only', 1)->where('identifier', 'like', 'LPPO_%')->get();
            }

            // LPPO Positions
            if($ctp_events->bird_coverage == 1){
                $birdPositions = MonitoredPosition::where('ctp_only', 1)->where('identifier', 'like', 'BIRD_%')->get();
            }

            // Combine Positions being Looked Into
            $monitoredPositions = $mainPositions->merge($lppoPositions)->merge($birdPositions);
        } else {
            $monitoredPositions = $mainPositions;
        }

        $vatsimData = new VATSIMClient();

        $czqoRoster = RosterMember::all()->pluck('user_id')->toArray();
        $eggxRoster = ExternalController::all()->pluck('id')->toArray();
        $allRoster = array_unique(array_merge($czqoRoster, $eggxRoster));

        $positionsFound = [];

        # PILOT NUMBERS
        $eggxPilots = 0;
        $czqoPilots = 0;
        $kznyPilots = 0;
        $lppoPilots = 0;
        $birdPilots = 0;

        // FIR Desingations
        $ganderFir = [
            [45.0, -30.0],
            [45.0, -40.0],
            [44.5, -40.0],
            [44.5, -50.0],
            [51.0, -50.0],
            [53.0, -54.0],
            [53.8, -55.0],
            [57.0, -59.0],
            [58.5, -60.4],
            [61.0, -63.0],
            [64.0, -63.0],
            [65.0, -60.0],
            [65.0, -57.8],
            [63.5, -55.7],
            [63.5, -39.0],
            [61.0, -30.0],
        ];

        $shanwickFir = [
            [45.0, -30.0],
            [61.0, -30.0],
            [61.0, -10.0],
            [57.0, -10.0],
            [57.0, -15.0],
            [49.0, -15.0],
            [48.5, -8.0],
            [45.0, -8.0]
        ];

        $nyFir = [
            [41.6, -67.0],
            [42.4, -61.2],
            [43.1, -57.9],
            [43.6, -55.8],
            [43.8, -54.9],
            [44.5, -50.0],
            [44.5, -40.0],
            [22.3, -40.0],
            [18.0, -45.0],
            [18.0, -61.5],
            [20.0, -61.9],
            [21.4, -63.4],
            [22.0, -64.0],
            [22.1, -65.1],
            [22.0, -66.7],
            [21.2, -67.7],
            [25.0, -68.5],
            [25.0, -73.2],
            [27.8, -74.8],
            [27.8, -76.3],
            [28.2, -76.4],
            [29.8, -76.9],
            [30.0, -77.0],
            [31.6, -77.0],
            [32.0, -77.0],
            [32.3, -77.0],
            [33.0, -76.8],
            [33.4, -76.5],
            [34.6, -75.7],
            [35.3, -75.2],
            [35.5, -74.9],
            [36.8, -74.6],
            [37.1, -74.7],
            [38.5, -74.0],
            [38.7, -73.9],
            [39.0, -73.7],
            [39.7, -73.2],
            [39.7, -73.2],
            [39.9, -73.0],
            [40.2, -72.8],
            [40.1, -72.5],
            [40.6, -70.9],
            [40.9, -69.3],
            [41.0, -69.0]
        ];

        $birdFir = [
            [66.8, -30.0],
            [66.9, -31.0],
            [68.3, -40.0],
            [70.3, -50.0],
            [70.5, -64.0],
            [65.0, -57.8],
            [63.5, -55.7],
            [63.5, -39.0],
            [61.0, -30.0],
            [61.0, -10.0],
            [60.7, -10.0],
            [61.0, -7.0],
            [61.0, -5.5],
            [61.0, 0.0],
            [61.5, 0.0],
            [63.0, 0.0],
            [63.3, 0.0],
            [65.8, 0.0],
            [66.8, -10.0],
            [66.8, -11.0],
            [66.8, -15.2],
            [66.8, -23.0],
            [66.8, -26.0]
        ];

        $lppoFir = [
            [45.0, -40.0],
            [45.0, -13.0],
            [43.0, -13.0],
            [42.0, -15.0],
            [36.5, -15.0],
            [34.3, -17.8],
            [34.0, -18.0],
            [33.8, -18.1],
            [33.6, -18.3],
            [33.3, -18.3],
            [33.1, -18.3],
            [32.8, -18.3],
            [32.6, -18.2],
            [32.3, -18.1],
            [32.1, -18.0],
            [31.9, -17.8],
            [31.7, -17.5],
            [31.7, -17.4],
            [30.0, -20.0],
            [30.0, -20.4],
            [30.0, -25.0],
            [24.0, -25.0],
            [17.0, -37.5],
            [22.3, -40.0]
        ];

        // Loop through the Pilots
        $pilots = $vatsimData->getPilots();
        $pilotInfo = [];

        foreach($pilots as $pilot){
            $inOCA = 0;

            $lat = $pilot->latitude;
            $lon = $pilot->longitude;

            if ($this->isPointInPolygon($lat, $lon, $ganderFir)) {
                $czqoPilots++;
                $inOCA = 1;
            }
            
            if ($this->isPointInPolygon($lat, $lon, $shanwickFir)) {
                $eggxPilots++;
                $inOCA = 1;
            }

            if ($this->isPointInPolygon($lat, $lon, $nyFir)) {
                $kznyPilots++;
                $inOCA = 1;
            }

            if ($this->isPointInPolygon($lat, $lon, $lppoFir)) {
                $lppoPilots++;
            }
            
            if ($this->isPointInPolygon($lat, $lon, $birdFir)) {
                $birdPilots++;
            }

            // If inside the OCA, Store Details to be used for later
            if($inOCA){
                $pilotInfo[] = [
                    'cid' => $pilot->cid,
                    'cs' => $pilot->callsign,
                ];
            }
        }

        // Reset Still Inside Markers
        $allAircraft = FIRAircraft::all();
        foreach($allAircraft as $ac){
            $ac->update([
                'still_inside' => null,
            ]);
        }
        
        // Check all Aircraft inside OCAs
        foreach($pilotInfo as $ac){
            FIRAircraft::UpdateorCreate(['cid' => $ac['cid'], 'callsign' => $ac['cs']],[
                'still_inside' => 1,
                'exited_oca' => null,
            ]);
        }

        // Mark those not inside OCA as exited - Will retain in the DB for 5 hours incase the aircraft goes EGGX > NYC via LPPO or EGGX > CZQO via BIRD
        $exitAircraft = FIRAircraft::where('still_inside', null)->where('exited_oca', null)->get();
        foreach($exitAircraft as $ea){
            $ea->update([
                'exited_oca' => Carbon::now(),
            ]);
        }

        // If Difference between Entry & Exit is over 30 Minutes, lets set points_recorded as 1 and add a point to the pilots table
        $addPoints = FIRAircraft::whereNull('point_recorded')->where('still_inside', 1)->where('created_at', '<=', Carbon::now()->subMinutes(30))->get();

        foreach($addPoints as $ap){

            $existingPilot = FIRPilots::find($ap->cid);

            $monthStats = $existingPilot && $existingPilot->month_stats !== null
                ? $existingPilot->month_stats + 1
                : 1;

            $yearStats = $existingPilot && $existingPilot->year_stats !== null
                ? $existingPilot->year_stats + 1
                : 1;

            $FIRPilots = FIRPilots::updateOrCreate(
                ['cid' => $ap->cid],
                ['month_stats' => $monthStats,
                'year_stats' => $yearStats],
            );

            $ap->update([
                'point_recorded' => 1,
            ]);
        }

        // Delete ID row if exited_oca time is more than 4 hours in the past
        $deleteAircraft = FIRAircraft::where('exited_oca', '<=', Carbon::now()->subMinutes(240))->get();
        foreach($deleteAircraft as $da){
            $da->delete();
        }

        $ganwickPilots = $eggxPilots + $czqoPilots;
        $partnerPilots = $ganwickPilots + $kznyPilots;
        $allPilots = $partnerPilots + $birdPilots + $lppoPilots;

        $pilots = FIRInfo::all()->first();
        $pilots->update([
            'eggx' => $eggxPilots,
            'czqo' => $czqoPilots,
            'ganwick' => $ganwickPilots,
            'kzny' => $kznyPilots,
            'partnership_firs' => $partnerPilots,
            'bird' => $birdPilots,
            'lppo' => $lppoPilots,
            'all' => $allPilots,
        ]);

        //Go through each position and process sessions for each of them
        foreach ($monitoredPositions as $position) {
            $controllers = $vatsimData->searchCallsign($position->identifier, false);

            foreach ($controllers as $controller) {

                $session = SessionLog::firstOrCreate([
                    'cid' => $controller->cid,
                    'callsign' => $controller->callsign,
                    'session_end' => null,
                ], [
                        'session_start' => Carbon::now(),
                        'emails_sent' => 0,
                        'is_ctp' => 0,
                        'monitored_position_id' => $position->id,
                        'roster_member_id' => RosterMember::where('cid', $controller->cid)->value('id') ?? null,
                    ]);

                    // Check if user entry exits
                    if($session->user){
                        // Instructing Training Session
                        if(str_contains($controller->callsign, '_I_') && $session->user->InstructorProfile){
                            $session->is_instructing = 1;
                            $session->save();
                        }

                        if($session->user->studentProfile){
                            // Student Training Session
                            if($session->user->studentProfile->current == 1){
                                $session->is_student = 1;
                                $session->save();
                            }
                        }
                    }

                    // Session During CTP
                    if($session->is_ctp == null){
                        if($ctp_events){
                            if(!$ctp_events->isEmpty()){
                                $session->is_ctp = 1;
                            } else {
                                $session->is_ctp = null;
                            }
                        }
                    }

                    // Controller Name for the Discord
                    if($session->user){
                        $name = $session->user->FullName('FLC');
                    } else {
                        $name = $controller->cid;
                    }        

                    // Check if Controller is Authorised to open a position (training/certified)
                    if (in_array($session->cid, $allRoster)) {
                        // Controller is authorised, send message if discord_id is not set
                        if($session->discord_id == null){

                            $isStudent = false;
                            if($session->user->studentProfile){
                                if($session->user->studentProfile->current == 1){
                                    $isStudent = true;
                                }
                            }

                            $isInstructor = false;
                            if(str_contains($controller->callsign, '_I_') && $session->user->InstructorProfile){
                                $isInstructor = true;
                            }

                            // Try Sending Discord Message
                            try{
                                $discord = new DiscordClient();
                                $discord_id = $discord->ControllerConnection($controller->callsign, $name, $isStudent, $isInstructor);

                                $session->discord_id = $discord_id;
                                $session->save();
                            } catch (\Exception $e) {
                                $discord = new DiscordClient();
                                $discord->sendMessageWithEmbed(env('DISCORD_WEB_LOGS'), 'Discord Controller Connect Error', $e->getMessage());
                            }

                            // Add Discord Role
                            if($session->user && $session->user->hasDiscord() && $session->user->member_of_czqo){
                                $discord = new DiscordClient();

                                $discord->assignRole($session->user->discord_user_id, '1278868454606377040');
                            }
                        }
                    } else {
                        // Controller is not authorised. Let Senior Team know.
                        if($session->discord_id == null && !$ctp_events){
                            // Send Discord Message
                            $discord = new DiscordClient();
                            $discord_id = $discord->sendMessageWithEmbed('482817715489341441', 'Controller Unauthorised to Control', '<@&482816721280040964>, '.$session->cid.' has just connected onto VATSIM as '.$session->callsign.' on <t:'.Carbon::now()->timestamp.':F>. 
                                
**They are not authorised to open this position.**');

                            // Save ID so it doesnt keep spamming
                            $session->discord_id = 0;
                            $session->save();
                        }
                    }

                array_push($positionsFound, $controller->callsign);
            }
        }

        //Check existing sessions in db
        $sessionLogs = SessionLog::whereNull('session_end')->get();
        foreach ($sessionLogs as $log) {
            // Controller has now disconnected
            if ((!in_array($log->callsign, $positionsFound)) || $vatsimData->searchCallsign($log->callsign, true)->cid != $log->cid) {
                $log->session_end = Carbon::now();
                $log->duration = $log->session_start->floatDiffInMinutes(Carbon::now()) / 60;
                $log->save();


                // Name if in DB, otherwise use CID
                if($log->user){
                    $name = $log->user->FullName('FLC');
                } else {
                    $name = $log->cid;
                }        

                // Discord ID i not null (message has not yet been sent)
                if($log->discord_id !== null){
                    // Update Disconnect Message
                        $discord = new DiscordClient();
                        $data = $discord->ControllerDisconnect($log->discord_id, $log->callsign, $name, $log->session_start, $log->duration);

                    // Remove Discord Role
                    if($log->user && $log->user->hasDiscord() && $log->user->member_of_czqo){
                        $discord = new DiscordClient();

                        $discord->removeRole($log->user->discord_user_id, '1278868454606377040');
                    }
                }

                $log->discord_id = null;
                $log->save;

                //If there is an associated roster member, give them the hours and set as active
                if ($rosterMember = $log->rosterMember) {
                    if (($rosterMember->certification == 'certified' || $rosterMember->certification == 'training')) {
                        $rosterMember->currency += $log->session_start->floatDiffInMinutes(Carbon::now()) / 60;
                        $rosterMember->monthly_hours += $log->session_start->floatDiffInMinutes(Carbon::now()) / 60;
                        $rosterMember->save();
                    }
                }
            }
        }
    }

    function isPointInPolygon($lat, $lon, $polygon) {
        $inside = false;
        $j = count($polygon) - 1;

        for ($i = 0; $i < count($polygon); $i++) {
            $xi = $polygon[$i][0]; $yi = $polygon[$i][1];
            $xj = $polygon[$j][0]; $yj = $polygon[$j][1];

            $intersect = (($yi > $lon) != ($yj > $lon)) &&
                        ($lat < ($xj - $xi) * ($lon - $yi) / ($yj - $yi) + $xi);
            if ($intersect) $inside = !$inside;
            $j = $i;
        }

        return $inside;
    }
}