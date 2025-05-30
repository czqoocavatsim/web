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

class DiscordAccountCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60;

    /**
     * Execute the job.
     *
     * @return void
     */

     public function tags()
    {
        return ['job:discord_account_check'];
    }

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
        $discord_not_in_system_ids = [];

        // Get List of Users in Discord
        $discord = new DiscordClient();
        $response = $discord->getClient()->get('guilds/'.env('DISCORD_GUILD_ID').'/members?limit=1000');
        $discord_members = json_decode($response->getBody(), true);

        // Discord Notification Calculations (Figure out who needs these roles)
        {
            // Initialise Notification ID roles
            $news_notify = [];
            $event_notify = [];
            $ctp_notify = [];
            $controller_notify = [];
            $pilot_notify = [];
            $tech_notify = [];

            // Get all the reactions for each type
            $news_react = $discord->getReactions('1347194167725522985', '1347464850254725131', urlencode('📢'));
            sleep(1);
            $event_react = $discord->getReactions('1347194167725522985', '1347464850254725131', urlencode('📆'));
            sleep(1);
            $ctp_react = $discord->getReactions('1347194167725522985', '1347464850254725131', '%F0%9F%87%A8');
            sleep(1);
            $controller_react = $discord->getReactions('1347194167725522985', '1347464850254725131', urlencode('🛰️'));
            sleep(1);
            $pilot_react = $discord->getReactions('1347194167725522985', '1347464850254725131', urlencode('✈️'));
            sleep(1);
            $tech_react = $discord->getReactions('1347194167725522985', '1347464850254725131', urlencode('🛠️'));

            // Lets now get some IDs
            foreach($news_react as $nr){
                if($nr['id'] !== "1118430230839840768"){
                    $news_notify[] = $nr['id'];
                }
            }
            foreach($event_react as $er){
                if($er['id'] !== "1118430230839840768"){
                    $event_notify[] = $er['id'];
                }
            }
            foreach($ctp_react as $cr){
                if($cr['id'] !== "1118430230839840768"){
                    $ctp_notify[] = $cr['id'];
                }
            }
            foreach($controller_react as $cor){
                if($cor['id'] !== "1118430230839840768"){
                    $controller_notify[] = $cor['id'];
                }
            }
            foreach($pilot_react as $pr){
                if($pr['id'] !== "1118430230839840768"){
                    $pilot_notify[] = $pr['id'];
                }
            }
            foreach($tech_react as $tr){
                if($tr['id'] !== "1118430230839840768"){
                    $tech_notify[] = $tr['id'];
                }
            }

            // dd($tech_notify);
        }

        // Loop through each Discord User and get some key information
        foreach($discord_members as $members){
            $discord_uids[] = $members['user']['id'];
            $discord_member_contents[] = $members;
        }

        // Find which users are in the Discord, but not registered with us

        // Get a complete list of Gander Oceanic Users
        $users = User::whereNotNull('discord_user_id')->get();

        // After the loop, find all Discord users who are not in your system
        foreach ($discord_uids as $discord_uid) {

            // Ignore Gary & Gander Bot
            if($discord_uid == 350995372627197954|| $discord_uid == 1118430230839840768){
                break;
            }

            // Check if the Discord user ID exists in the users database
            $user_exists = false;
            foreach ($users as $user) {
                if ($user->discord_user_id == $discord_uid) {
                    $user_exists = true;
                    break;
                }
            }

            // If the user is not in the system, add the Discord user ID to the list
            if (!$user_exists) {
                $discord_not_in_system_ids[] = $discord_uid;

                $discord->assignRole($discord_uid, '1372584622818332763');
            }
        }

        // dd($discord_not_in_system_ids);

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
                            'zny_certified' => 1302030442916089866,

                            's1' => 1342639858027462769,
                            's2' => 1342640729012568145,
                            's3' => 1342640763183435807,
                            'c1' => 1342640783211233280,
                            'c3' => 1342640799837585468,
                            'i1' => 1342640831043211344,
                            'i3' => 1347454523676819516,
                            'sup' => 720502070683369563,
                            'adm' => 1342640950412967937,

                            'ppl' => 1342642295157297203,
                            'ir' => 1342642432147460281,
                            'cmel' => 1342642434299002961,
                            'atpl' => 1342642436408606851,
                            'fi' => 1342642438162088091,
                            'fe' => 1342642440846311556,

                            'm1' => 1369692686344523787,
                            'm2' => 1369692752656203927,
                            'm3' => 1369692872470822932,
                            'm4' => 1369692937478344704,
                            
                            'news_notify' => 1347476285542236160,
                            'event_notify' => 1347476363472273418,
                            'ctp_notify' => 1347476367574569020,
                            'controller_notify' => 1347476371236192287,
                            'pilot_notify' => 1347476375321182228,
                            'tech_notify' => 1347476378915700777,
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
                        $externalController = ExternalController::find($user->id);
                        if ($externalController !== null) {
                            
                            if($externalController->visiting_origin == "eggx"){
                                array_push($mainRoles, $discordRoleIds['certified']);
                                array_push($mainRoles, $discordRoleIds['shanwick_certified']);
                            } elseif($externalController->visiting_origin == "zny") {
                                array_push($mainRoles, $discordRoleIds['certified']);
                                array_push($mainRoles, $discordRoleIds['zny_certified']);
                            }
                        }

                        //VATSIM Ratings Calculation
                        {
                            // Calculate Controller Rating
                            if ($user->rating_short == 'S1') {
                                array_push($mainRoles, $discordRoleIds['s1']);
                            }
                            if($user->rating_short == 'S2') {
                                array_push($mainRoles, $discordRoleIds['s2']);
                            }
                            if($user->rating_short == 'S3') {
                                array_push($mainRoles, $discordRoleIds['s3']);
                            }
                            if($user->rating_short == 'C1') {
                                array_push($mainRoles, $discordRoleIds['c1']);
                            }
                            if($user->rating_short == 'C3') {
                                array_push($mainRoles, $discordRoleIds['c3']);
                            }
                            if($user->rating_short == 'I1') {
                                array_push($mainRoles, $discordRoleIds['i1']);
                            }
                            if($user->rating_short == 'I3') {
                                array_push($mainRoles, $discordRoleIds['i3']);
                            }
                            if($user->rating_short == 'SUP') {
                                array_push($mainRoles, $discordRoleIds['sup']);
                            }
                            if($user->rating_short == 'ADM') {
                                array_push($mainRoles, $discordRoleIds['adm']);
                            }


                            // Calculate Pilot Rating
                            if($user->pilotrating_short == 'PPL'){
                                array_push($mainRoles, $discordRoleIds['ppl']);
                            }
                            if($user->pilotrating_short == 'IR'){
                                array_push($mainRoles, $discordRoleIds['ir']);
                            }
                            if($user->pilotrating_short == 'CMEL'){
                                array_push($mainRoles, $discordRoleIds['cmel']);
                            }
                            if($user->pilotrating_short == 'ATPL'){
                                array_push($mainRoles, $discordRoleIds['atpl']);
                            }
                            if($user->pilotrating_short == 'FI'){
                                array_push($mainRoles, $discordRoleIds['fi']);
                            }
                            if($user->pilotrating_short == 'FE'){
                                array_push($mainRoles, $discordRoleIds['fe']);
                            }

                            // Calculate Military Rating
                            if($user->militaryrating_short == 'M1'){
                                array_push($mainRoles, $discordRoleIds['m1']);
                            }
                            if($user->militaryrating_short == 'M2'){
                                array_push($mainRoles, $discordRoleIds['m2']);
                            }
                            if($user->militaryrating_short == 'M3'){
                                array_push($mainRoles, $discordRoleIds['m3']);
                            }
                            if($user->militaryrating_short == 'M4'){
                                array_push($mainRoles, $discordRoleIds['m4']);
                            }
                        }

                        // Discord Notification Role Assignment
                        {
                            if(in_array($user->discord_user_id, $news_notify)){
                                array_push($mainRoles, $discordRoleIds['news_notify']);
                            }
                            if(in_array($user->discord_user_id, $event_notify)){
                                array_push($mainRoles, $discordRoleIds['event_notify']);
                            }
                            if(in_array($user->discord_user_id, $ctp_notify)){
                                array_push($mainRoles, $discordRoleIds['ctp_notify']);
                            }
                            if(in_array($user->discord_user_id, $controller_notify)){
                                array_push($mainRoles, $discordRoleIds['controller_notify']);
                            }
                            if(in_array($user->discord_user_id, $pilot_notify)){
                                array_push($mainRoles, $discordRoleIds['pilot_notify']);
                            }
                            if(in_array($user->discord_user_id, $tech_notify)){
                                array_push($mainRoles, $discordRoleIds['tech_notify']);
                            }
                        }

                        // Check Assigned Discord Roles, and keep them assigned
                        $roleIdsToCheck = [
                            1214350179151650898, //Exam Request
                            635449323089559572, //AFV Dev Role
                            634656628335050762, //Shanwick Team
                            497351197280174080, //VATCAN Divisional Staff
                            497359834010615809, //VATSIM Senior Staff
                            1300054143532138516, //VATSYS Beta Tester
                            1278868454606377040, //Currently Online
                            695274344141815891, //Discord Nitro Role
                            1372439231426990211, //No Discord Account Registered
                        ];

                        foreach ($roleIdsToCheck as $roleId) {
                            if (in_array($roleId, $discord_member['roles'])) {
                                $mainRoles[] = $roleId;  // Add the role ID to mainRoles if present in user's roles
                            }
                        }

                        $discord_roles = array_unique($mainRoles);


                        // Name Format for ZQO Members and Other Members
                        {
                            if($user->staffProfile && $user->staffProfile->group_id == 1){
                                $name = $user->Fullname('FL')." - ZQO".$user->staffProfile->id;
                            } else {
                                $name = $user->FullName('FLC');
                            }
    
                            // Check to ensure User name is less than 32 characters. If not, step down progressivly to find name format
                            # FNAME + LINITAL + CID
                            if (strlen($name) > 32) {
                                if ($user->staffProfile && $user->staffProfile->group_id == 1) {
                                    $name = $user->fname." ".substr($user->lname, 0, 1)." - ".$user->staffProfile->id;
                                } else {
                                    $name = $user->fname." ".substr($user->lname, 0, 1)." - ".$user->id;
                                }
                            }

                            # FNAME + CID
                            if (strlen($name) > 32) {                          
                                if ($user->staffProfile && $user->staffProfile->group_id == 1) {
                                    $name = $user->fname." "." - ".$user->staffProfile->id;
                                } else {
                                    $name = $user->fname." - ".$user->id;
                                }
                            }
    
                            // If its still greater than 32, then just show the CID
                            if (strlen($name) > 32) {
                                $name = $user->id;
                            }
                        }


                        // Full list of staff roles
                        $staffRoleIDs = [
                            'discord_admin' => 1328612019619758193,
                            'fir_director' => 524435557472796686,
                            'operations_director' => 783558030842527784,
                            'training_events_director' => 783558130100731924,
                            'events_marketing_director' =>783558227174227979 ,
                            'fir_sector_manager' => 783558276334747678,
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
                                case 'FIR Director':
                                    array_push($staffRoles, $staffRoleIDs['fir_director']);
                                    array_push($staffRoles, $staffRoleIDs['senior_staff']);
                                    break;
                                case 'Operations Director':
                                    array_push($staffRoles, $staffRoleIDs['operations_director']);
                                    array_push($staffRoles, $staffRoleIDs['senior_staff']);
                                    break;
                                case 'Training & Events Director':
                                    array_push($staffRoles, $staffRoleIDs['training_events_director']);
                                    array_push($staffRoles, $staffRoleIDs['senior_staff']);
                                    break;
                                case 'FIR Sector Manager':
                                    array_push($staffRoles, $staffRoleIDs['fir_sector_manager']);
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

                        // $discord->sendMessageWithEmbed('1299248165551210506', 'USER: '.$name, $message);

                    }
                     

    
                } else {
                    ## User is NOT in the discord
                    $not_in_discord++;

                    // Update DB Information
                    $user->member_of_czqo = false;
                    $user->save();
                }

        }

        if($user_updated > 0){
            $discord->sendMessage('482860026831175690', "DISCORD UPDATE: ".$user_updated." users updated.");
        }
    }

}