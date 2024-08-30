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
        // Counter Variables (For Message at End)
        $first_notice = 0; //305 Days without controlling
        $second_notice = 0; //335 Days without controlling
        $third_notice = 0; //358 Days without controlling
        $termination_notice = 0; //365 Days without controlling (one year)
        
        $roster_controllers = RosterMember::all();

        // Lets now go through each RosterMember (gotta update dem all)
        foreach($roster_controllers as $roster){

            // get sessions within the last 12 months
            $sessions = SessionLog::where('cid', $roster->cid)->where('created_at', '>=', Carbon::now()->subMonths(12))->orderBy('created_at', 'asc')->get();
            $last_session = SessionLog::where('cid', $roster->cid)->orderBy('created_at', 'desc')->first();

            // Set some variables (default values)
            $currency = 0; //Assume no connections
            $active_status = 1; //Assume roster member is active
            $removeController = false; //Remove controller (false by default)

            // Go through each session to get some information
            foreach($sessions as $s){
                $currency += $s->duration;
            }



            // Check if there was a last session
            if($last_session != null && $roster->certification === "certified"){
                
                // Currency is less than 1 hour and last connection was 305 days ago.
                if($roster->active && $currency < 1 && $last_session->created_at->diffInDays(now()) == 305){
                    $active_status = 0;
    
                    // Send Message to user that they have been set as inactive
                    if($roster->user->member_of_czqo && $roster->user->hasDiscord()){
                        $discord = new DiscordClient();
                        $discord->sendDM($roster->user->discord_user_id, 'Roster Status set as Inactive', 'Hello!
                        
    Your status has been set as Inactive with Gander Oceanic. This is because you have not controlled at least one hour, within the last 305 days.
    
    You have 60 days to control at least one hour, otherwise you will be removed as a certified controller.
    
    If you have any questions, please reach out to the Gander Oceanic team on our Discord
    
    **Regards,
    Gander Oceanic**');
    
                    }

                    $first_notice++;
                };

                // User in inactive, has < 1hr of activity, and has not controlled for 335 days
                if(!$roster->active && $currency < 1 && $last_session->created_at->diffInDays(now()) == 335){

                    $second_notice++;
                }


                // User is inactive, has <1hr of activity and has not controlled for 358 Days
                if(!$roster->active && $currency < 1 && $last_session->created_at->diffInDays(now()) == 358){

                    $third_notice++;
                }


            // No Session was returned within the last 365 Days.
            } else {
                if($roster->certification === "certified"){
                    $removeController = true;
                }
            }


            // Save Roster Information
            $roster->active = $active_status;
            $roster->currency = $currency;
            $roster->save();

            // Delete Controller if they should be removed.
            if($removeController === true){
                // Send Message to user that they have been set as inactive
                if($roster->user->member_of_czqo && $roster->user->hasDiscord()){
                    $discord = new DiscordClient();
                    $discord->sendDM($roster->user->discord_user_id, 'Gander Oceanic Certification Expired', 'Hello!
                    
Your Oceanic Certification has now been removed. This is because you have failed to control at least 1 hour, within the last 365 days.

Should you wish to regain your certification, please apply to do so via the Gander Website.

**Regards,
Gander Oceanic**');

                $roster->user->removeRole('Certified Controller');
                $roster->user->assignRole('Guest');
                $roster->delete();
                $termination_notice++;
            }
        }
    }

    // Send Web Notification if any changes have been made
    $discord = new DiscordClient();
    $discord->sendMessageWithEmbed(env('DISCORD_WEB_LOGS'), 'AUTO: Roster Inactivity Update', 
    '60 Days till Removed: '.$first_notice.'
    30 Days till Removed: '.$second_notice.'
    7 Days till Removed: '.$third_notice.'
    Removed from Roster: '.$termination_notice
    );
}}
