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

    public function handle()
    {
        // Timeout length (seconds)
        ini_set('max_execution_time', 1200);

        // Script Start Time
        $start_time = Carbon::now();

        // Initialise some variables
        $checked_users = 0;
        $accounts_not_linked = 0;
        $discord_uids = [];

        // Get List of Users in Discord
        $discord = new DiscordClient();
        $response = $discord->getClient()->get('guilds/'.env('DISCORD_GUILD_ID').'/members?limit=1000');
        $discord_members = json_decode($response->getBody(), true);

        // dd($discord_members);

        // Loop through each Discord User and get some key information
        foreach($discord_members as $members){
            $discord_uids[] = $members['user']['id'];
            // dd($members['user']);
        }
        // dd($discord_uids);

        // Get a complete list of Gander Oceanic Users
        $users = User::all();

        // Loop through each DB User
        foreach($users as $user){

            // Skip is discord_user_id is null
            if($user->discord_user_id == null){
                continue;
            } else {
                $checked_users++;
            }

            // Add a Sleep Timer - Allows API to not block
            sleep(2);

            // Check if user is currently in Discord
            foreach($discord_uids as $discord_uid){
                

                if($user->discord_user_id == $discord_uid){
                    ## User is in the Discord
    
                    // Get Discord Member Information
                    $discord_member = $discord->getClient()->get('guilds/'.env('DISCORD_GUILD_ID').'/members/'.$discord_uid);
                    $discord_member = json_decode($discord_member->getBody(), true);

                    // Discord Account is Linked. Remove from Check
                    $key = array_search($user->discord_user_id, $discord_uids);
                    if ($key !== false) {
                        unset($discord_uids[$key]);
                    }
    
                    // Update DB information
                    $user->member_of_czqo = 1;
                    $user->discord_username = $discord_member['user']['username'];
                    $user->discord_avatar = $user->avatar ? 'https://cdn.discordapp.com/avatars/'.$user->discord_user_id.'/'.$discord_member['user']['avatar'].'.png' : null;
                    $user->save();

                    // Skip if User has 'Senior Staff' Role, skip as bot doesnt have power
                    if(in_array('482816721280040964', $discord_member['roles'])){
                        continue;
                    }

                    // Skip if User has 'Staff Member' Role, lets skip them for now as roles need to be finished
                    if(in_array('752767906768748586', $discord_member['roles'])){
                        continue;
                    }
    
                    // Roles Calculation
                    {
                        // Generic Roles users can have by default
                        $rolesToAdd = [];
                        $discordRoleIds = [
                            'guest'      => 482835389640343562,
                            'training'   => 482824058141016075,
                            'certified'  => 482819739996127259,
                            'supervisor' => 720502070683369563,
                        ];

                        //Add the Member role to each user
                        array_push($rolesToAdd, $discordRoleIds['guest']);

                        //Roster Member?
                        if (RosterMember::where('user_id', $user->id)->exists()) {
                            //What status do they have?
                            $rosterProfile = RosterMember::where('user_id', $user->id)->first();
                            switch ($rosterProfile->certification) {
                                case 'certified':
                                    array_push($rolesToAdd, $discordRoleIds['certified']);
                                    break;
                                case 'training':
                                    array_push($rolesToAdd, $discordRoleIds['training']);
                                    break;
                            }
                        }

                        //Supervisor?
                        if ($user->rating_short == 'SUP') {
                            array_push($rolesToAdd, $discordRoleIds['supervisor']);
                        }

                        // Check Assigned Discord Roles, and keep them assigned
                        $roleIdsToCheck = [
                            635449323089559572, //AFV Dev Role
                            634656628335050762, //Shanwick Team
                            497351197280174080, //VATCAN Divisional Staff
                            497359834010615809, //VATSIM Senior Staff
                            1257807978531389561]; //VATSYS Beta Tester

                        foreach ($roleIdsToCheck as $roleId) {
                            if (in_array($roleId, $discord_member['roles'])) {
                                $rolesToAdd[] = $roleId;  // Add the role ID to rolesToAdd if present in user's roles
                            }
                        }

                        // dd($rolesToAdd);

                    }
                    
    
                    // Update user
                    $discord->getClient()->patch('guilds/'.env('DISCORD_GUILD_ID').'/members/'.$user->discord_user_id, [
                        'json' => [
                            'nick' => $user->FullName('FLC'),
                            'roles' => $rolesToAdd,
                        ]
                    ]);
    
                } else {
                    ## User is NOT in the discord
    
                    // Update DB Information
                    $user->member_of_czqo = 0;
                    $user->save();
                }

            }

        }

        // Add Role to Users not Connected to Gander Oceanic
        foreach($discord_uids as $discord_uid){
            $accounts_not_linked++; //records that Account Not Linked Role Assigned

            sleep(3);

            // add role
            $discord->getClient()->put('guilds/'.env('DISCORD_GUILD_ID').'/members/'.$discord_uid.'/roles/1297422968472997908');
        }



        // Record Information for Discord

        // Beginning
        $update_content = "Full list of functions completed this week for Discord Users, <@200426385863344129>";

        $update_content .= "\n\n **__Accounts:__**";

        // Users which are linked in Discord
        $update_content .= "\n- Linked to Discord: ".$checked_users." (name/roles updated)";

        // Accounts not linked
        $update_content .= "\n- Not Linked to Discord: ".$accounts_not_linked." (role assigned)";

        // Completion Time
        $end_time = Carbon::now();
        $seconds = $start_time->diffInSeconds($end_time);
        $update_content .= "\n\n**__Script Time:__**";
        $update_content .= "\n- Script Time: ".$seconds." seconds.";

        $discord->sendMessageWithEmbed(env('DISCORD_WEB_LOGS'), 'WEEKLY: Discord User Update', $update_content);
    }

}
