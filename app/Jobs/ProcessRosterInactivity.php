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
use App\Notifications\Roster\RosterStatusChanged;
use App\Notifications\Roster\RemovedFromRoster;
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
        $roster_controllers = RosterMember::all();

        // Lets now go through each RosterMember (gotta update dem all)
        foreach($roster_controllers as $roster){

            // get sessions within the last 12 months
            $sessions = SessionLog::where('cid', $roster->cid)->where('created_at', '>=', Carbon::now()->subMonths(12))->orderBy('created_at', 'asc')->get();
            $last_session = SessionLog::where('cid', $roster->cid)->orderBy('created_at', 'desc')->first();
            // dd($last_session);

            // Set some variables (default values)
            $currency = 0; //Assume no connections
            $active_status = 1; //Assume roster member is active

            // Go through each session to get some information
            foreach($sessions as $s){
                $currency += $s->duration;
            }

            // Currency is less than 1 hour and last connection was 305 days ago.
            if($roster->active && $currency < 1 && $last_session->created_at->diffInDays(now()) >= 305){
                $active_status = 0;

                // Send Message to user that they have been 
                if($roster->user->member_of_czqo && $roster->user->hasDiscord()){
                    $discord = new DiscordClient();
                    $discord->sendDM($roster->user->discord_user_id, 'Roster Status set as Inactive', 'Hello!
                    
Your status has been set as Inactive with Gander Oceanic. This is because you have not controlled at least one hour, within the last 305 days.

You have 60 days to control at least one hour, otherwise you will be removed as a certified controller.

If you have any questions, please reach out to the Gander Oceanic team on our Discord

**Regards,
Gander Oceanic**');

                }
            }

            // Save Roster Information
            $roster->active = $active_status;
            $roster->currency = $currency;
            $roster->save();
        }
        
    }
}
