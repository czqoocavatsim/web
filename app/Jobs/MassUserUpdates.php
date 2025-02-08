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
use App\Models\Network\ExternalController;
use App\Notifications\Training\Instructing\RemovedAsStudent;
use App\Models\Training\Instructing\Students\StudentStatusLabel;
use App\Models\Training\Instructing\Links\StudentStatusLabelLink;
use Carbon\Carbon;

class MassUserUpdates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 48000;

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle()
    {
        // Timeout length (seconds)
        ini_set('max_execution_time', 48000);

        // Guzzle Client Initialization
        $guzzle = new Client(['timeout' => 1000, 'connect_timeout' => 1000]);

        // Script Start Time
        $start_time = Carbon::now();

        $user = User::all();

        foreach($user as $u){
            $vatsim_data = $guzzle->request('GET', 'https://api.vatsim.net/v2/members/'.$member['id']);
            $vatsim = json_decode($vatsim_data->getBody(), true);

            
        }

        if($user_updated > 0){
        // Record Information for Discord
        // Beginning
        $update_content = "Quarterly User Database Updates were just completed";

        $update_content .= "\n\n **__Updated Users:__**";

        // Completion Time
        $end_time = Carbon::now();
        $update_content .= "\n\n**__Script Time:__**";
        $update_content .= "\n- Script Time: " . $start_time->diffForHumans($end_time, ['parts' => 2, 'short' => true, 'syntax' => Carbon::DIFF_ABSOLUTE]) . ".";

        $discord->sendMessageWithEmbed('482860026831175690', 'QUARTERLY: Mass User Updates', $update_content);
        }
    }

}