<?php

namespace App\Jobs;

use App\Models\Roster\RosterMember;
use App\Models\Network\SessionLog;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Network\TwoMonthInactivityReminder;
use App\Notifications\Network\OneMonthInactivityReminder;
use App\Notifications\Network\OneWeekInactivityReminder;
use App\Notifications\Network\ControllerTerminated;
use App\Services\DiscordClient;

class ProcessRosterInactivity implements ShouldQueue
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
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Counter Variables (For Message at End)
        $first_notice = 0; //305 Days without controlling
        $second_notice = 0; //335 Days without controlling
        $third_notice = 0; //358 Days without controlling
        $termination_notice = 0; //365 Days without controlling (one year)

        $first_names = [];
        $second_names = [];
        $third_names = [];
        $termination_names = [];
        
        $roster_controllers = RosterMember::all();

        // Lets now go through each RosterMember (gotta update dem all)
        foreach($roster_controllers as $roster){

            // get sessions within the last 12 months
            $sessions = SessionLog::where('cid', $roster->cid)->where('created_at', '>=', Carbon::now()->subMonths(12))->orderBy('created_at', 'asc')->get();
            $last_session = SessionLog::where('cid', $roster->cid)->where('created_at', '>=', Carbon::now()->subMonths(12))->orderBy('created_at', 'desc')->first();

            // user being checked.
            $name = $roster->user->discord_user_id;
            
            // Set some variables (default values)
            $currency = 0; //Assume no connections
            $active_status = 1; //Assume roster member is active

            // Go through each session to get some information
            foreach($sessions as $s){
                
                // //Counts sessions only greater than 30mins in length
                // if($s->duration > 0.49){
                //     $currency += $s->duration;
                // }

                // Total Currency
                $currency += $s->duration;
            }



            // Check if there was a last session
            if($last_session != null && $roster->certification === "certified"){
                
                // Currency is less than 1 hour and last connection was 305 days ago.
                if($roster->active && $currency < 1 && $last_session->created_at->diffInDays(now()) == 305){
                    $active_status = 0;

                    $first_names[] = "<@".$name.">";

                    $first_notice++;
                };

                // User in inactive, has < 1hr of activity, and has not controlled for 335 days
                if($currency < 1 && $last_session->created_at->diffInDays(now()) == 335){
                    $second_names[] = "<@".$name.">";

                    $second_notice++;
                }


                // User is inactive, has <1hr of activity and has not controlled for 358 Days
                if($currency < 1 && $last_session->created_at->diffInDays(now()) == 358){
                    $third_names[] = "<@".$name.">";

                    $third_notice++;
                }

                // Save Roster Information based of above if statements
                $roster->active = $active_status;
                $roster->currency = $currency;
                $roster->save();


            // No Session was returned within the last 365 Days.
            } else {
                if($roster->certification === "certified"){
                $termination_notice++;
                $termination_names[] = "<@".$name.">";
                
                $roster->user->removeRole('Certified Controller');
                $roster->user->assignRole('Guest');
                $roster->delete();
                }
            }
    }

    #Generate Discord Message
    $message_contents = "The following changes have been made to the Controller Roster.";

    if($first_notice != 0){
        $message_contents .= "\n\n**60 Days Until Removed (Set as Inactive)**";

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
