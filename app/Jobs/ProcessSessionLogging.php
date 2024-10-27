<?php

namespace App\Jobs;

use App\Models\Network\MonitoredPosition;
use App\Models\Network\SessionLog;
use App\Models\Network\ShanwickController;
use App\Models\Roster\RosterMember;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
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
        //BEGIN CONTROLLER SESSION CHECK

        //Get monitored positions
        $monitoredPositions = MonitoredPosition::all();

        $vatsimData = new VATSIMClient();

        $czqoRoster = RosterMember::all()->pluck('user_id')->toArray();
        $eggxRoster = ShanwickController::all()->pluck('controller_cid')->toArray();
        $allRoster = array_unique(array_merge($czqoRoster, $eggxRoster));

        $positionsFound = [];

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
                        'monitored_position_id' => $position->id,
                        'roster_member_id' => RosterMember::where('cid', $controller->cid)->value('id') ?? null,
                    ]);

                    if($session->user){
                        $name = $session->user->fullName('FLC');
                    } else {
                        $name = $controller->cid;
                    }        

                    // Check if Controller is Authorised to open a position (training/certified)
//                     if (in_array($session->cid, $allRoster)) {
//                         // Controller is authorised, send message if discord_id is not set
//                         if($session->discord_id == null){
//                             // Discord Message
//                             try{
//                                 $discord = new DiscordClient();
//                                 $discord_id = $discord->ControllerConnection($controller->callsign, $name);
    
//                                 $session->discord_id = $discord_id;
//                                 $session->save();
//                             } catch (\Exception $e) {
//                                 $discord = new DiscordClient();
//                                 $discord->sendMessageWithEmbed(env('DISCORD_WEB_LOGS'), 'Discord Controller Connect Error', $e->getMessage());
//                             }

//                             // Add Discord Role
//                             if($session->user && $session->user->hasDiscord() && $session->user->member_of_czqo){
//                                 $discord = new DiscordClient();

//                                 $discord->assignRole($session->user->discord_user_id, '1278868454606377040');
//                             }
//                         }
//                     } else {
//                         // Controller is not authorised. Let Senior Team know.
//                         if($session->discord_id == null){
//                             try{
//                                 // Send Discord Message
//                                 $discord = new DiscordClient();
//                                 $discord_id = $discord->sendMessageWithEmbed('482817715489341441', 'Controller Unauthorised to Control', '<@&482816721280040964>, '.$session->cid.' has just connected onto VATSIM as '.$session->callsign.' on <t:'.Carbon::now()->timestamp.':F>. 
                                
// **They are not authorised to open this position.**');

//                                 // Save ID so it doesnt keep spamming
//                                 $session->discord_id = $discord_id;
//                                 $session->save();
//                             } catch (\Exception $e) {
//                                 $discord = new DiscordClient();
//                                 $discord->sendMessageWithEmbed(env('DISCORD_WEB_LOGS'), 'Unauthorised Controller Notification Error', $e->getMessage());
//                             }
//                         }
//                     }

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

                // dd($log);

                if($log->user){
                    $name = $log->user->fullName('FLC');
                } else {
                    $name = $log->cid;
                }        

                // Discord ID is not null (message has not yet been sent)
                if($log->discord_id !== null){
                    // Update Disconnect Message
                    try{
                        $discord = new DiscordClient();
                        $data = $discord->ControllerDisconnect($log->discord_id, $log->callsign, $name, $log->session_start, $log->duration);
                    } catch (\Exception $e) {
                        $discord = new DiscordClient();
                        $discord->sendMessageWithEmbed(env('DISCORD_WEB_LOGS'), 'Discord Controller Disconnect Error', $e->getMessage());
                    }

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

                    // Set variables
                    $currency = $log->session_start->floatDiffInMinutes(Carbon::now()) / 60;
                    $monthly_hours = $log->session_start->floatDiffInMinutes(Carbon::now()) / 60;

                    // Controller is either Certified or In Training
                    if (($rosterMember->certification == 'certified' || $rosterMember->certification == 'training')) {

                        // Only add hours if more than 30mins (Disabled to 01mins until policy update)
                        if($currency > 0.00){
                            $rosterMember->currency += $currency;
                            $rosterMember->monthly_hours += $monthly_hours;
                            $rosterMember->save();
                        }

                        // Set user as active if more than 1 hour
                        if($rosterMember->currency > 0.99){
                            $rosterMember->active = 1;
                            $rosterMember->save();
                        }
                    }
                }
            }
        }
    }
}