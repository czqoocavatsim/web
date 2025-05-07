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
use App\Models\Network\ExternalController;
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
        $external_controllers = ExternalController::where('currency', '>', 0)->get();

        // Set Variables
        $total_hours = 0;
        
        foreach($roster_member as $roster){
            $total_hours += $roster->monthly_hours;
        }

        // Compose the message
        $message = 'It is the beginning of a new month, so here the stats for Gander Oceanic during ' . Carbon::now()->subMonth()->format('F, Y') . "\n\n";

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

        $message .= "\nA massive thank you to all of the above Controllers for providing ATC Services within Gander during ".Carbon::now()->subMonth()->format('F, Y');

        // Send the Announcement
        $discord = new DiscordClient();
        $discord->sendMessageWithEmbed(env('DISCORD_COMMUNITY'), 'Gander Oceanic Operations | '.Carbon::now()->subMonth()->format('F, Y'), $message);

        foreach($roster_member as $roster){
            $roster->monthly_hours = 0;
            $roster->save();
        }

        foreach($external_controllers as $member){
            $member->monthly_hours = 0;
            $member->save();
        }
    }

}
