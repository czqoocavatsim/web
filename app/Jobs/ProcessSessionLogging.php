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
use Illuminate\Support\Facades\Log;
use Vatsimphp\VatsimData;

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
        //Get VATSIMData instance
        $vatsimData = new VatsimData();
        $dataLoaded = $vatsimData->loadData();

        //If no data...
        if (!$dataLoaded) {
            Log::error('ProcessSessionLogs job: VATSIMPhp failed to load data');
            return;
        }

        //Get monitored positions
        $monitoredPositions = MonitoredPosition::all()->sortBy('staff_only');

        //Go through each position and process sessions for each of them
        foreach ($monitoredPositions as $position) {
            //Get sessions with the positions callsign
            $vatsimSessionInstances = $vatsimData->searchCallsign($position->identifier)->toArray();

            //If there is an active session
            if ($activeSession = $position->activeSession()) {
                if (empty($vatsimSessionInstances)) { //If the session isn't detected online anymore
                    //Update and end session
                    $activeSession->session_end = Carbon::now();
                    $activeSession->duration = $activeSession->session_start->floatDiffInMinutes(Carbon::now()) / 60;
                    $activeSession->save();

                    //If there is an associated roster member, give them the hours
                    if ($rosterMember = $activeSession->rosterMember) {
                        if ($rosterMember->certification == 'certified' && $rosterMember->active) {
                            $rosterMember->currency += $activeSession->session_start->floatDiffInMinutes(Carbon::now()) / 60;
                            $rosterMember->monthly_hours += $activeSession->session_start->floatDiffInMinutes(Carbon::now()) / 60;
                            $rosterMember->save();
                        }
                    }
                }
            } else { //Looking for a new session
                if(empty($vatsimSessionInstances)){ // Should be empty if there's no sessions found, not with an index of 0
                    Log::info('No sessions found for '.$position->identifier);
                    continue;
                }
                // Should only be executing if there's a session in progress
                $instance = $vatsimSessionInstances[0];

                //Create a new session
                $session = SessionLog::create([
                    'roster_member_id'      => RosterMember::whereCid($instance['cid'])->first()->id ?? null,
                    'cid'                   => $instance['cid'],
                    'session_start'         => Carbon::parse($instance['time_logon']),
                    'monitored_position_id' => $position->id,
                    'emails_sent'           => 0,
                ]);
                /*
                                //Send notifications if staff only, not certified, etc
                                if ($position->staff_only && ($session->rosterMember == null || $session->rosterMember->certification == 'not_certified'))
                                {
                                    Notification::route('mail', CoreSettings::find(1)->emailfirchief)->notify(new ControllerNotCertified($session));
                                    $session->emails_sent++;
                                    $session->save();
                                }
                 */
                $session->save();
            }
        }
    }
}
