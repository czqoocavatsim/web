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
use App\Models\Network\ShanwickController;
use App\Notifications\Training\Instructing\RemovedAsStudent;
use App\Models\Training\Instructing\Students\StudentStatusLabel;
use App\Models\Training\Instructing\Links\StudentStatusLabelLink;
use Carbon\Carbon;

class DiscordAccountCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 7200;

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle()
    {
        // Timeout length (seconds)
        ini_set('max_execution_time', 7200);

        // Script Start Time
        $start_time = Carbon::now();

        // Initialise some variables
        $checked_users = 0;
        $accounts_not_linked = 0;
        $in_discord = 0;
        $not_in_discord = 0;
        $discord_uids = [];

        // Get List of Users in Discord
        $discord = new DiscordClient();
        $response = $discord->getClient()->get('guilds/'.env('DISCORD_GUILD_ID').'/members?limit=1000');
        $discord_members = json_decode($response->getBody(), true);

        // Loop through each Discord User and get some key information
        foreach($discord_members as $members){
            $discord_uids[] = $members['user']['id'];
            // dd($members['user']);
        }

        // Get a complete list of Gander Oceanic Users
        $users = User::whereNotNull('discord_user_id')->get();

        // Loop through each DB User
        foreach($users as $user){

            // Skip is discord_user_id is null
            if($user->discord_user_id == null){
                continue;
            } else {
                $checked_users++;
            }

            // Check if user is currently in Discord
                if (in_array($user->discord_user_id, $discord_uids)) {

                    ## User is in the Discord
                    $discord_uid = $user->discord_user_id;
                    $in_discord++;

                    sleep(1);

                    // Get Discord Member Information
                    $discord_member = $discord->getClient()->get('guilds/'.env('DISCORD_GUILD_ID').'/members/'.$discord_uid);
                    $discord_member = json_decode($discord_member->getBody(), true);

                    // Discord Account is Linked. Remove from Check
                    $key = array_search($user->discord_user_id, $discord_uids);
                    if ($key !== false) {
                        unset($discord_uids[$key]);
                    }
    
                    // Update DB information
                    $user->member_of_czqo = true;
                    $user->discord_username = $discord_member['user']['username'];
                    $user->discord_avatar = $user->avatar ? 'https://cdn.discordapp.com/avatars/'.$user->discord_user_id.'/'.$discord_member['user']['avatar'].'.png' : null;
                    $user->save();

                    // Skip Gary (Discord Owner)
                    if($user->discord_user_id == 350995372627197954){
                        continue;
                    }

                    // // Skip Joshua (Broken for him)
                    // if($user->discord_user_id == 200426385863344129){
                    //     continue;
                    // }
    
                    // Roles Calculation
                    {
                        // Roles assigned to general members
                        $mainRoles = [];
                        $staffRoles = [];
                        $discordRoleIds = [
                            'member'      => 482835389640343562,
                            'training'   => 482824058141016075,
                            'certified'  => 482819739996127259,
                            'gander_certified'  => 1297507926222573568,
                            'shanwick_certified' => 1297508027280396349,
                            'supervisor' => 720502070683369563,
                        ];

                        //Add the Member role to each user
                        array_push($mainRoles, $discordRoleIds['member']);

                        //Gander Roster Member
                        if (RosterMember::where('user_id', $user->id)->exists()) {
                            //What status do they have?
                            $rosterProfile = RosterMember::where('user_id', $user->id)->first();
                            switch ($rosterProfile->certification) {
                                case 'certified':
                                    array_push($mainRoles, $discordRoleIds['certified']);
                                    array_push($mainRoles, $discordRoleIds['gander_certified']);
                                    break;
                                case 'training':
                                    array_push($mainRoles, $discordRoleIds['training']);
                                    break;
                            }
                        }

                        // Shankwick Roster Members
                        $shanwickRoster = ShanwickController::where('controller_cid', $user->id)->first();
                        if ($shanwickRoster) {
                            array_push($mainRoles, $discordRoleIds['certified']);
                            array_push($mainRoles, $discordRoleIds['shanwick_certified']);
                        }

                        //Supervisor?
                        if ($user->rating_short == 'SUP') {
                            array_push($mainRoles, $discordRoleIds['supervisor']);
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
                                $mainRoles[] = $roleId;  // Add the role ID to mainRoles if present in user's roles
                            }
                        }

                        $discord_roles = array_unique($mainRoles);

                        // Name Format for ZQO Members and Other Members
                        if($user->staffProfile && $user->staffProfile->group_id == 1){
                            $name = $user->Fullname('FL')." ZQO".$user->staffProfile->id;
                        } else {
                            $name = $user->FullName('FLC');
                        }

                        // Update user with main roles - Will temp remove staff roles
                        $discord->getClient()->patch('guilds/'.env('DISCORD_GUILD_ID').'/members/'.$user->discord_user_id, [
                            'json' => [
                                'nick' => $name,
                                'roles' => $discord_roles,
                            ]
                        ]);

                        // Full list of staff roles
                        $staffRoleIDs = [
                            'discord_admin' => 752756810104176691,
                            'oca_chief' => 524435557472796686,
                            'deputy_oca_chief' => 783558030842527784,
                            'chief_instructor' => 783558130100731924,
                            'events_marketing_director' =>783558227174227979 ,
                            'operations_director' => 783558276334747678,
                            'it_director' => 783558309717868544,
                            'senior_staff' => 482816721280040964,

                            'staff_instructor' => 482816758185590787,
                            'staff_web' => 482817113023578125,
                            'events_marketing_staff' => 666760228372480020,
                            'operations_staff' => 770615953465409536,
                            'staff_member' => 752767906768748586,
                        ];

                        // Role Groups
                        if($user->hasRole('Administrator')) {
                            array_push($staffRoles, $staffRoleIDs['discord_admin']);
                        }
                        
                        if($user->hasRole('Instructor') || $user->hasRole('Assessor')) {
                            array_push($staffRoles, $staffRoleIDs['staff_instructor']);
                            array_push($staffRoles, $staffRoleIDs['staff_member']);
                        }

                        if($user->hasRole('Web Team')) {
                            array_push($staffRoles, $staffRoleIDs['staff_web']);
                            array_push($staffRoles, $staffRoleIDs['staff_member']);
                        }

                        if($user->hasRole('Events and Marketing Team')) {
                            array_push($staffRoles, $staffRoleIDs['events_marketing_staff']);
                            array_push($staffRoles, $staffRoleIDs['staff_member']);
                        }

                        if($user->hasRole('Operations Team')) {
                            array_push($staffRoles, $staffRoleIDs['operations_staff']);
                            array_push($staffRoles, $staffRoleIDs['staff_member']);
                        }

                        if($user->staffProfile && $user->staffProfile->group_id == 1){
                            switch ($user->staffProfile->position) {
                                case 'FIR Chief':
                                    array_push($staffRoles, $staffRoleIDs['oca_chief']);
                                    array_push($staffRoles, $staffRoleIDs['senior_staff']);
                                    break;
                                case 'Deputy FIR Chief':
                                    array_push($staffRoles, $staffRoleIDs['deputy_oca_chief']);
                                    array_push($staffRoles, $staffRoleIDs['senior_staff']);
                                    break;
                                case 'Chief Instructor':
                                    array_push($staffRoles, $staffRoleIDs['chief_instructor']);
                                    array_push($staffRoles, $staffRoleIDs['senior_staff']);
                                    break;
                                case 'Events and Marketing Director':
                                    array_push($staffRoles, $staffRoleIDs['events_marketing_director']);
                                    array_push($staffRoles, $staffRoleIDs['senior_staff']);
                                    break;
                                case 'Operations Director':
                                    array_push($staffRoles, $staffRoleIDs['operations_director']);
                                    array_push($staffRoles, $staffRoleIDs['senior_staff']);
                                    break;
                                case 'IT Director':
                                    array_push($staffRoles, $staffRoleIDs['it_director']);
                                    array_push($staffRoles, $staffRoleIDs['senior_staff']);
                                    break;
                            }
                        }
                    }

                    foreach ($staffRoles as $role){
                        sleep(0.2);

                        // add role
                        $discord->getClient()->put('guilds/'.env('DISCORD_GUILD_ID').'/members/'.$discord_uid.'/roles/'.$role);
                    }
                     

    
                } else {
                    ## User is NOT in the discord
                    $not_in_discord++;

                    // Update DB Information
                    $user->member_of_czqo = false;
                    $user->save();
                }

        }

        // Add Role to Users not Connected to Gander Oceanic
        foreach($discord_uids as $discord_uid){
            $accounts_not_linked++; //records that Account Not Linked Role Assigned

            sleep(0.2);

            // add role
            $discord->getClient()->put('guilds/'.env('DISCORD_GUILD_ID').'/members/'.$discord_uid.'/roles/1297422968472997908');
        }



        // Record Information for Discord
        // Beginning
        $update_content = "Full list of functions completed this week for Discord Users";

        $update_content .= "\n\n **__Accounts:__**";

        // Users which are linked in Discord
        $update_content .= "\n- Total Accounts: ".$checked_users." (name/roles updated)";
        $update_content .= "\n- Linked & In Discord: ".$in_discord;
        $update_content .= "\n- Linked but not in Discord: ".$not_in_discord;

        // Accounts not linked
        $update_content .= "\n- Not Linked to Discord: ".$accounts_not_linked." (role assigned)";

        // Completion Time
        $end_time = Carbon::now();
        $update_content .= "\n\n**__Script Time:__**";
        $update_content .= "\n- Script Time: " . $start_time->diffForHumans($end_time, ['parts' => 2, 'short' => true, 'syntax' => Carbon::DIFF_ABSOLUTE]) . ".";


        $discord->sendMessageWithEmbed(env('DISCORD_SERVER_LOGS'), 'DAILY: Discord User Update', $update_content);
    }

}
