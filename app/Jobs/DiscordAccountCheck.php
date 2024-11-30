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
        $user_updated = 0;
        $discord_uids = [];
        $discord_member_contents = [];
        $in_discord_name = [];

        // Get List of Users in Discord
        $discord = new DiscordClient();
        $response = $discord->getClient()->get('guilds/'.env('DISCORD_GUILD_ID').'/members?limit=1000');
        $discord_members = json_decode($response->getBody(), true);

        // Loop through each Discord User and get some key information
        foreach($discord_members as $members){
            $discord_uids[] = $members['user']['id'];
            $discord_member_contents[] = $members;
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

                    // dd($discord_uids);

                    ## User is in the Discord
                    $discord_uid = $user->discord_user_id;
                    $in_discord++;

                    foreach($discord_member_contents as $discord_members2){

                        if ($discord_members2['user']['id'] == $discord_uid) {
                            $discord_member = $discord_members2;

                            // dd($discord_uid);

                            break;
                        }
                    }

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
                            1214350179151650898, //Exam Request
                            635449323089559572, //AFV Dev Role
                            634656628335050762, //Shanwick Team
                            497351197280174080, //VATCAN Divisional Staff
                            497359834010615809, //VATSIM Senior Staff
                            1300054143532138516]; //VATSYS Beta Tester

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

                    // Combine mainRoles and staffRoles into a single array
                    $combinedRoles = array_merge($mainRoles, $staffRoles);
                    $combinedRoles = array_unique($combinedRoles);

                    // Any Differences?
                    $rolesToAssign = array_diff($combinedRoles, $discord_member['roles']);
                    $rolesToRemove = array_diff($discord_member['roles'], $combinedRoles);

                    if (!empty($rolesToAssign) || !empty($rolesToRemove) || $name !== $discord_member['nick']) {
                        
                        // Get Name
                        $in_discord_name[] = $name;

                        // Sleep for 1 second (let API catch up)
                        sleep(1);

                        $message = "Assign Roles:";
                        foreach($rolesToAssign as $role){
                            $message .= "\n- $role";
                        }
                        $message .= "\n\nRemove Roles:";
                        foreach($rolesToRemove as $role){
                            $message .= "\n- $role";
                        }
                        $message .= "\n\n**User Roles Updated!**";

                        $user_updated++;

                        // Update user with main roles - Will temp remove staff roles
                        $discord->getClient()->patch('guilds/'.env('DISCORD_GUILD_ID').'/members/'.$user->discord_user_id, [
                            'json' => [
                                'nick' => $name,
                                'roles' => $discord_roles,
                            ]
                        ]);

                        foreach ($staffRoles as $role){

                            // Slow down multi role add. Allow API to catch up
                            sleep(2.75);

                            // add role
                            $discord->getClient()->put('guilds/'.env('DISCORD_GUILD_ID').'/members/'.$discord_uid.'/roles/'.$role);
                        }

                        $discord->sendMessageWithEmbed('1299248165551210506', 'USER: '.$name, $message);

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
            
            // Skip the Bot (Gander)
            if($discord_uid == 1118430230839840768){
                continue;
            }

            // Skip the Bot (QFA100)
            if($discord_uid == 1133048493850771616){
                continue;
            }

            // Skip Server Owner (Gary)
            if($discord_uid == 350995372627197954){
                continue;
            }

            $accounts_not_linked++; //records that Account Not Linked Role Assigned

            sleep(1);

            // // Update user with main roles - Will temp remove staff roles
            // $discord->getClient()->patch('guilds/'.env('DISCORD_GUILD_ID').'/members/'.$user->discord_user_id, [
            //     'json' => [
            //         'nick' => $name,
            //         'roles' => $discord_roles,
            //     ]
            // ]);

            // add role
            $discord->getClient()->put('guilds/'.env('DISCORD_GUILD_ID').'/members/'.$discord_uid.'/roles/1297422968472997908');
        }

        if($user_updated > 0){
        // Record Information for Discord
        // Beginning
        $update_content = "Updates were conducted for Discord.";

        $update_content .= "\n\n **__Updated Users:__**";
        foreach($in_discord_name as $name){
            $update_content .= "\n- ".$name;
        }

        $update_content .= "\n\n **__General Information:__**";

        // Users which are linked in Discord
        $update_content .= "\n- Accounts Linked in Core: ".$checked_users;
        $update_content .= "\n- Linked - in Discord: ".$in_discord;
        $update_content .= "\n- Linked - not in Discord: ".$not_in_discord;

        // Accounts not linked
        $update_content .= "\n- Not Linked - in Discord: ".$accounts_not_linked." (No Account Role Assigned)";

        // Completion Time
        $end_time = Carbon::now();
        $update_content .= "\n\n**__Script Time:__**";
        $update_content .= "\n- Script Time: " . $start_time->diffForHumans($end_time, ['parts' => 2, 'short' => true, 'syntax' => Carbon::DIFF_ABSOLUTE]) . ".";

        $discord->sendMessageWithEmbed(env('DISCORD_SERVER_LOGS'), 'DAILY: Discord User Update', $update_content);
        }
    }

}
