<?php

namespace App\Jobs;

use App\Models\Network\MonitoredPosition;
use App\Models\Network\SessionLog;
use App\Models\Roster\RosterMember;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\VATSIMClient;

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
        //BEGIN CONTROLLER SESSION CHECK

        //Get monitored positions
        $monitoredPositions = MonitoredPosition::all();

        $vatsimData = new VATSIMClient();

        $positionsFound = [];

        //Go through each position and process sessions for each of them
        foreach ($monitoredPositions as $position) {
            $controllers = $vatsimData->searchCallsign($position->identifier, false);

            foreach ($controllers as $controller) {

                SessionLog::firstOrCreate([
                    'cid' => $controller->cid,
                    'callsign' => $controller->callsign,
                    'session_end' => null,
                ], [
                        'session_start' => Carbon::now(),
                        'emails_sent' => 0,
                        'monitored_position_id' => $position->id,
                        'roster_member_id' => RosterMember::where('cid', $controller->cid)->value('id') ?? null,
                    ]);

                array_push($positionsFound, $controller->callsign);
            }
        }

        //Check existing sessions in db
        $sessionLogs = SessionLog::whereNull('session_end')->get();
        foreach ($sessionLogs as $log) {
            if ((!in_array($log->callsign, $positionsFound)) || $vatsimData->searchCallsign($log->callsign, true)->cid != $log->cid) {
                $log->session_end = Carbon::now();
                $log->duration = $log->session_start->floatDiffInMinutes(Carbon::now()) / 60;
                $log->save();

                //If there is an associated roster member, give them the hours
                if ($rosterMember = $log->rosterMember) {
                    if (($rosterMember->certification == 'certified' || $rosterMember->certification == 'training') && $rosterMember->active) {
                        $rosterMember->currency += $log->session_start->floatDiffInMinutes(Carbon::now()) / 60;
                        $rosterMember->monthly_hours += $log->session_start->floatDiffInMinutes(Carbon::now()) / 60;
                        $rosterMember->save();
                    }
                }
            }
        }
    }
}