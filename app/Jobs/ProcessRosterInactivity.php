<?php

namespace App\Jobs;

use App\Models\Users\User;
use App\Models\Network\SessionLog;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Roster\QuarterBeforeRemoval;
use App\Notifications\Roster\TwoMonthFromRemoval;
use App\Notifications\Roster\OneMonthFromRemoval;
use App\Notifications\Roster\SevenDaysFromRemoval;
use App\Services\DiscordClient;

class ProcessRosterInactivity implements ShouldQueue

{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $timeout = 600;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Timeout length (seconds)
        ini_set('max_execution_time', 600);

        // Counter Variables (For Message at End)
        $q4_notice = 0;
        $first_notice = 0; //1NOV message sent out
        $second_notice = 0; //1DEC message sent out
        $third_notice = 0; //24DEC message
        $termination_notice = 0; //31DEC Message Sent Out

        $first_names = [];
        $second_names = [];
        $third_names = [];
        $termination_names = [];
        
        $roster_controllers = RosterMember::all();
        // dd($roster_controllers);

        // Lets now go through each RosterMember (gotta update dem all)
        foreach($roster_controllers as $roster){

            // get sessions since start of year
            $sessions = SessionLog::where('cid', $roster->cid)->where('created_at', '>=', Carbon::now()->startOfYear())->orderBy('created_at', 'asc')->get();

            // Name of user being checked - Will link to the Users Roster Profile
            $name = "[" . $roster->user->fullName('FLC') . "](" . route('training.admin.roster.viewcontroller', $roster->cid) . ")";

            // Set some variables (default values)
            $currency = 0; //Assume no connections
            if($roster->active) { //Assume based off current set
                $active_status = 1;
            } else {
                $active_status = 0;
            }

            // Go through each session to get some information
            foreach($sessions as $s){
                //Counts sessions only greater than 30mins in length
                if($s->duration > 0.49){
                    $currency += $s->duration;
                }
            }

            // If Currency is greater than 1, set status active
            if($currency > 6){
                $active_status = 1;
            }

            // 1OCT - Q4 Begins and Controller has not achieved 6hrs
            if($roster->certification == "certified" && $roster->active && Carbon::now()->format('d/m') == "01/10" && $roster->currency < 6) {
                $active_status = 0;

                $q4_names[] = $name ." (". $roster->currency ." hrs)";

                $q4_notice++;
                
                Notification::send($roster->user, new QuarterBeforeRemoval($roster->user, $currency));
            }

            // 1NOV - 2 Month Activity Check
            if($roster->certification == "certified" && $roster->active && Carbon::now()->format('d/m') == "01/11" && $roster->currency < 6) {
                $active_status = 0;

                $first_names[] = $name ." (". $roster->currency ." hrs)";

                $first_notice++;
                
                Notification::send($roster->user, new TwoMonthFromRemoval($roster->user, $currency));
            }

            // 1DEC - 1 Month till Removal
            if($roster->certification == "certified" && Carbon::now()->format('d/m') == "01/12" && $roster->currency < 6){
                $active_status = 0;

                $second_names[] = $name ." (". $roster->currency ." hrs)";

                $second_notice++;

                Notification::send($roster->user, new OneMonthFromRemoval($roster->user, $currency));
            }

            // 24DEC - 7 Days Till Removal
            if($roster->certification == "certified" && Carbon::now()->format('d/m') == "24/12" && $roster->currency < 6){
                $active_status = 0;

                $third_names[] = $name ." (". $roster->currency ." hrs)";

                $third_notice++;

                Notification::send($roster->user, new SevenDaysFromRemoval($roster->user, $currency));
            }

            // End Of Year - Reset Time & Get Users to Remove
            if($roster->certification == "certified" && Carbon::now()->format('d/m') == "31/12"){

                // User to be terminated
                if($roster->currency < 6){
                    $termination_names[] = $name ."(". $roster->currency ." hrs)";

                    $termination_notice++;
                }

                // Set Total Currency back to Zero (New Year Begins)
                $currency = 0;
            }

            // Save Roster Information based of above if statements
            $roster->active = $active_status;
            $roster->currency = $currency;
            $roster->save();
        }

    #Generate Discord Message
    $message_contents = "The following changes have been made to the Controller Roster.";

    if($q4_notice != 0){
        $message_contents .= "\n\n**90 Days To Complete Activity**";

        foreach($q4_names as $n){
            $message_contents .= "\n- ".$n;
        }
    }
    
    if($first_notice != 0){
        $message_contents .= "\n\n**60 Days To Complete Activity (Set as Inactive)**";

        foreach($first_names as $n){
            $message_contents .= "\n- ".$n;
        }
    }

    if($second_notice != 0){
        $message_contents .= "\n\n**30 Days Until Removed**";

        foreach($second_names as $n){
            $message_contents .= "\n- ".$n;
        }
    }
    
    if($third_notice != 0){
        $message_contents .= "\n\n**7 Days Until Removed**";

        foreach($third_names as $n){
            $message_contents .= "\n- ".$n;
        }
    }

    if($termination_notice != 0){
        $message_contents .= "\n\n**Removed from Roster**";

        foreach($termination_names as $n){
            $message_contents .= "\n- ".$n;
        }
    }

    // Send Web Notification if any changes have been made
    if($first_notice != 0 || $second_notice !== 0 || $third_notice !== 0 || $termination_notice !== 0){
        $discord = new DiscordClient();
        $discord->sendMessageWithEmbed(env('DISCORD_SERVER_LOGS'), 'ROSTER: Currency Update', $message_contents);
    }
}}
