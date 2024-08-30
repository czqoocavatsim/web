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
use App\Notifications\Training\Instructing\RemovedAsStudent;
use App\Models\Training\Instructing\Students\StudentStatusLabel;
use App\Models\Training\Instructing\Links\StudentStatusLabelLink;
use Carbon\Carbon;

class DiscordAccountCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */

    protected $signature = 'discord:accountupdate';

    public function handle()
    {
        // Initialise some variables
        $discord_not_linked = 0;

        // Get List of Users in Discord
        $discord = new DiscordClient();
        $response = $discord->getClient()->get('guilds/'.env('DISCORD_GUILD_ID').'/members?limit=1000');
        // $response = $discord->getClient()->get('guilds/'.env('DISCORD_GUILD_ID').'/members');
        $discord_members = json_decode($response->getBody(), true);
        // $discord_members[0]['nick'] = "Joshua Micallef 1342084";
        // dd($discord_members);

        // Get All Users where member_of_czqo = 0 & used_connect = 1
        $users_not_linked = User::where('member_of_czqo', 0)->where('used_connect', 1)->get()->toArray();
        // dd($users_not_linked);

        // foreach ($threads_data['threads'] as $thread) {
        //     if (strpos($thread['name'], $cid) !== false) {

        // Go through each Discord Member
        foreach($discord_members as $discord_user){

            foreach($users_not_linked as $users){

                // dd($users);

                // Variables to be compared
                $name = $discord_user['nick'];
                $cid = $users['id'];

                // CID matches ID of user not linked
                if (strpos($name, $cid) !== false) {
                    $discord = new DiscordClient();

                    $discord->assignRole($discord_user['user']['id'], '1278606316906090527');

                    // Add one role
                    $discord_not_linked++;
                }

            }
        }

        // Tell the log chat
        $discord->sendMessageWithEmbed(env('DISCORD_WEB_LOGS'), 'AUTO: Users Not Linked',$discord_not_linked. ' members are not linked with CZQO. Role has been assigned');
    }

}
