<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use GuzzleHttp\Client;
use App\Services\DiscordClient;
use App\Models\Training\Instructing\Records\TrainingSession;
use App\Models\Users\User;
use App\Models\Roster\RosterMember;
use App\Models\News\HomeNewControllerCert;
use App\Notifications\Training\Instructing\RemovedAsStudent;
use App\Models\Training\Instructing\Students\StudentStatusLabel;
use App\Models\Training\Instructing\Links\StudentStatusLabelLink;
use Carbon\Carbon;

class ProcessMonthlyBreakdown implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle()
    {
        // Get Collection Values
        $roster_member = RosterMember::all();
        $top_3 = RosterMember::all()->sortByDesc(function ($member) {return (float) $member->monthly_hours;})->take(3);
        $new_controllers = HomeNewControllerCert::where('timestamp', '>=', Carbon::now()->subMonths(1))->get();

        // Set Variables
        $total_hours = 0;
        
        foreach($roster_member as $roster){
            $total_hours += $roster->monthly_hours;
        }

        // Compose the message
        $message = 'It is the beginning of a new month, so here are some wonderful stats for ' . Carbon::now()->subMonth()->format('F, Y') . "\n\n";

        $message .= "**__Total Controller Hours__**\n";
        $message .= "- " . $total_hours . " hours\n";

        $message .= "\n**__Top 3 Controllers__**\n";
        foreach($top_3 as $t) {
            if($t->user->discord_user_id === null){
                $message .= "- " . $t->user->fullName('FLC') . " - ".$t->monthly_hours." hours\n";
            } else {
                $message .= "- <@" . $t->user->discord_user_id . "> - ".$t->monthly_hours." hours\n";
            }
        }

        $message .= "\n**__New Certified Controllers__**\n";
        foreach($new_controllers as $nc) {
            if($nc->controller->discord_user_id === null){
                $message .= "- " . $nc->controller->fullName('FLC') . "\n";
            } else {
                $message .= "- <@" . $nc->controller->discord_user_id . ">\n";
            }
        }

        $message .= "\nWell done to everyone for your contributions to Gander Oceanic over the last Month! Enjoy ".Carbon::now()->format('F, Y');

        // Send the Announcement
        $discord = new DiscordClient();
        $discord->sendMessageWithEmbed(env('DISCORD_ANNOUNCEMENTS'), 'Gander Oceanic Operations Breakdown - '.Carbon::now()->subMonth()->format('F, Y'), $message);

        foreach($roster_member as $roster){
            $roster->monthly_hours = 0.0;
            $roster->save();
        }
    }

}
