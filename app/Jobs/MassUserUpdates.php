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
use Illuminate\Support\Facades\Notification;
use App\Notifications\Network\EnrouteRatingUpgrade;
use App\Notifications\Training\Instructing\RemovedAsStudent;
use App\Models\Training\Instructing\Students\StudentStatusLabel;
use App\Models\Training\Instructing\Links\StudentStatusLabelLink;
use Carbon\Carbon;

class MassUserUpdates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 79000;

    /**
     * Execute the job.
     *
     * @return void
     */

    //  Delay the job retry to 5 minutes.
    public function backoff()
    {
        return [300];
    }


    public function handle()
    {
        // Timeout length (seconds)
        ini_set('max_execution_time', 60000);

        // Guzzle Client Initialization
        $guzzle = new Client(['timeout' => 100, 'connect_timeout' => 55]);

        // Discord Bot Variable Initialisation
        $start_time = Carbon::now();
        $user_updated = 0;
        $user_not_updated = 0;
        $vatsim_gdpr = 0;
        $vatsim_c1_upgrade = 0;
        $vatsim_api_failed = 0;

        $update_rating = 0;
        $update_region = 0;
        $update_division = 0;
        $update_subdivision = 0;
        $update_pilot = 0;
        $update_military = 0;

        // VATSIM Region List
        $vatsim_region_pull = $guzzle->request('GET', 'https://api.vatsim.net/api/regions');
        $vatsim_regions = json_decode($vatsim_region_pull->getBody(), true);

        // VATSIM Division List
        $vatsim_division_pull = $guzzle->request('GET', 'https://api.vatsim.net/api/divisions');
        $vatsim_divisions = json_decode($vatsim_division_pull->getBody(), true);

        // VATSIM Subdivision List
        $vatsim_subdivisions_pull = $guzzle->request('GET', 'https://api.vatsim.net/api/subdivisions');
        $vatsim_subdivisions = json_decode($vatsim_subdivisions_pull->getBody(), true);

        // VATSIM Rating List
        $vatsim_ratings = [
            [
                "id" => -1,
                "short" => "INAC",
                "long" => "Inactive"
            ],
            [
                "id" => 0,
                "short" => "SUS",
                "long" => "Suspended"
            ],
            [
                "id" => 1,
                "short" => "OBS",
                "long" => "Observer"
            ],
            [
                "id" => 2,
                "short" => "S1",
                "long" => "Tower Trainee"
            ],
            [
                "id" => 3,
                "short" => "S2",
                "long" => "Tower Controller"
            ],
            [
                "id" => 4,
                "short" => "S3",
                "long" => "Senior Student"
            ],
            [
                "id" => 5,
                "short" => "C1",
                "long" => "Enroute Controller"
            ],
            [
                "id" => 6,
                "short" => "C2",
                "long" => "Controller 2 (not in use)"
            ],
            [
                "id" => 7,
                "short" => "C3",
                "long" => "Senior Controller"
            ],
            [
                "id" => 8,
                "short" => "I1",
                "long" => "Instructor"
            ],
            [
                "id" => 9,
                "short" => "I2",
                "long" => "Instructor 2 (not in use)"
            ],
            [
                "id" => 10,
                "short" => "I3",
                "long" => "Senior Instructor"
            ],
            [
                "id" => 11,
                "short" => "SUP",
                "long" => "Supervisor"
            ],
            [
                "id" => 12,
                "short" => "ADM",
                "long" => "Administrator"
            ]
        ];

        $vatsim_pilot_ratings = [
            [
                "id" => 1,
                "short" => "PPL",
                "long" => "Private Pilot License"
            ],
            [
                "id" => 3,
                "short" => "IR",
                "long" => "Instrument Rating"
            ],
            [
                "id" => 7,
                "short" => "CMEL",
                "long" => "Commercial Multi-Engine License"
            ],
            [
                "id" => 15,
                "short" => "ATPL",
                "long" => "Air Transport Pilot License"
            ],
            [
                "id" => 31,
                "short" => "FI",
                "long" => "Flight Instructor"
            ],
            [
                "id" => 63,
                "short" => "FE",
                "long" => "Flight Examiner"
            ]
        ];  
        
        $vatsim_mil_ratings = [
            [
                "id" => 1,
                "short" => "M1",
                "long" => "Military Pilot License"
            ],
            [
                "id" => 3,
                "short" => "M2",
                "long" => "Military Instrument Rating"
            ],
            [
                "id" => 7,
                "short" => "M3",
                "long" => "Military Multi-Engine Rating"
            ],
            [
                "id" => 15,
                "short" => "M4",
                "long" => "Military Mission Ready Pilot"
            ]            
        ];

        // Get full User list
        $user = User::all();
        $total_users = count($user);
        $users_counted = 0;

        $discord = new DiscordClient();
        $thread_id = $discord->createThread(env('DISCORD_SERVER_LOGS'), 'System User Updates - Week '.Carbon::now()->weekOfYear .', '.Carbon::now()->Format('Y'));

        $discord->sendMessage($thread_id, '# System User Update Start');

        foreach($user as $u){
            // Ignore the following IDs (not actually members) & Ignore VATSIM GDPR Accounts (Details have been updated already)
            if($u->id == 1 || $u->id == 2 || $u->id == 4 || $u->vatsim_gdpr_account == 1 || str_contains($u->id, '100000')){
                continue;
            }

            // Try to Update the User, or Record as a Failure
            try {
                $vatsim_data = $guzzle->request('GET', 'https://api.vatsim.net/v2/members/' . $u->id);
                $vatsim = json_decode($vatsim_data->getBody(), true);
            
                // Match Info to the VATSIM Array Definitions
                {
                    $region = array_filter($vatsim_regions, fn($r) => $r['id'] === $vatsim['region_id']);
                    $region = reset($region);
            
                    $division = array_filter($vatsim_divisions, fn($d) => $d['id'] === $vatsim['division_id']);
                    $division = reset($division);
            
                    $subdivision = array_filter($vatsim_subdivisions, fn($sd) => $sd['code'] === $vatsim['subdivision_id']);
                    $subdivision = reset($subdivision) ?: null;
                    $subdivisionFullname = $subdivision['fullname'] ?? null;
            
                    $rating = array_filter($vatsim_ratings, fn($s) => $s['id'] === $vatsim['rating']);
                    $rating = reset($rating);

                    $pilot_rating = array_filter($vatsim_pilot_ratings, fn($pr) => $pr['id'] === $vatsim['pilotrating']);
                    $pilot_rating = reset($pilot_rating) ?: null;
                    $pilotratingshortname = $pilot_rating['short'] ?? null;
                    $pilotratinglongname = $pilot_rating['long'] ?? null;

                    $military_rating = array_filter($vatsim_mil_ratings, fn($mr) => $mr['id'] === $vatsim['militaryrating']);
                    $military_rating = reset($military_rating) ?: null;
                    $militaryratingshortname = $military_rating['short'] ?? null;
                    $militaryratinglongname = $military_rating['long'] ?? null;
                }
            
                // Lets Update the User Data based off the API Return
                $needsUpdate = false;
                $changes = [];

                // Look at each individual section to cross check if any changes are required
                {
                    if ($u->rating_id != $vatsim['rating']) {
                        $needsUpdate = true;
                        $changes[] = "\n- rating_id ({$u->rating_id} > {$vatsim['rating']})";
                        $update_rating++;
                    }
                    if ($u->rating_short != $rating['short']) {
                        $needsUpdate = true;
                        $changes[] = "\n- rating_short ({$u->rating_short} > {$rating['short']})";
                    }
                    if ($u->rating_long != $rating['long']) {
                        $needsUpdate = true;
                        $changes[] = "\n- rating_long ({$u->rating_long} > {$rating['long']})";
                    }
                    if ($u->rating_GRP != $rating['long']) {
                        $needsUpdate = true;
                        $changes[] = "\n- rating_GRP ({$u->rating_GRP} > {$rating['long']})";
                    }
                    if ($u->pilotrating_id != $vatsim['pilotrating']) {
                        $needsUpdate = true;
                        $changes[] = "\n- pilotrating_id ({$u->pilotrating_id} > {$vatsim['pilotrating']})";
                        $update_pilot++;
                    }
                    if ($u->pilotrating_short != $pilotratingshortname) {
                        $needsUpdate = true;
                        $changes[] = "\n- pilotrating_short ({$u->pilotrating_short} > {$pilotratingshortname})";
                    }
                    if ($u->pilotrating_long != $pilotratinglongname) {
                        $needsUpdate = true;
                        $changes[] = "\n- pilotrating_long ({$u->pilotrating_long} > {$pilotratinglongname})";
                    }
                    if ($u->militaryrating_id != $vatsim['militaryrating']) {
                        $needsUpdate = true;
                        $changes[] = "\n- militaryrating_id ({$u->militaryrating_id} > {$vatsim['militaryrating']})";
                        $update_military++;
                    }
                    if ($u->militaryrating_short != $militaryratingshortname) {
                        $needsUpdate = true;
                        $changes[] = "\n- militaryrating_short ({$u->militaryrating_short} > {$militaryratingshortname})";
                    }
                    if ($u->militaryrating_long != $militaryratinglongname) {
                        $needsUpdate = true;
                        $changes[] = "\n- militaryrating_long ({$u->militaryrating_long} > {$militaryratinglongname})";
                    }
                    if ($u->reg_date != Carbon::parse($vatsim['reg_date'])->format('Y-m-d H:i:s')) {
                        $needsUpdate = true;
                    }
                    if ($u->region_code != $vatsim['region_id']) {
                        $needsUpdate = true;
                        $changes[] = "\n- region_code ({$u->region_code} > {$vatsim['region_id']})";
                        $update_region++;
                    }
                    if ($u->region_name != $region['name']) {
                        $needsUpdate = true;
                        $changes[] = "\n- region_name ({$u->region_name} > {$region['name']})";
                    }
                    if ($u->division_code != $vatsim['division_id']) {
                        $needsUpdate = true;
                        $changes[] = "\n- division_code ({$u->division_code} > {$vatsim['division_id']})";
                        $update_division++;
                    }
                    if ($u->division_name != $division['name']) {
                        $needsUpdate = true;
                        $changes[] = "\n- division_name ({$u->division_name} > {$division['name']})";
                    }
                    if ($u->subdivision_code != $vatsim['subdivision_id']) {
                        $needsUpdate = true;
                        $changes[] = "\n- subdivision_code ({$u->subdivision_code} > {$vatsim['subdivision_id']})";
                        $update_subdivision++;
                    }
                    if ($u->subdivision_name != $subdivisionFullname) {
                        $needsUpdate = true;
                        $changes[] = "\n- subdivision_name ({$u->subdivision_name} > {$subdivisionFullname})";
                    }
                }

                if($u->rating_short == "S3" && $rating['short'] == "C1"){
                    $vatsim_c1_upgrade++;

                    Notification::send($u, new EnrouteRatingUpgrade($u));
                }

                if ($needsUpdate) {
                    $u->rating_id = $vatsim['rating'];
                    $u->rating_short = $rating['short'];
                    $u->rating_long = $rating['long'];
                    $u->rating_GRP = $rating['long'];
                    $u->pilotrating_id = $vatsim['pilotrating'];
                    $u->pilotrating_short = $pilotratingshortname;
                    $u->pilotrating_long = $pilotratinglongname;
                    $u->militaryrating_id = $vatsim['militaryrating'];
                    $u->militaryrating_short = $militaryratingshortname;
                    $u->militaryrating_long = $militaryratinglongname;
                    $u->reg_date = Carbon::parse($vatsim['reg_date'])->format('Y-m-d H:i:s');
                    $u->region_code = $vatsim['region_id'];
                    $u->region_name = $region['name'];
                    $u->division_code = $vatsim['division_id'];
                    $u->division_name = $division['name'];
                    $u->subdivision_code = $vatsim['subdivision_id'];
                    $u->subdivision_name = $subdivisionFullname;
                    $u->updated_at = Carbon::now()->format('Y-m-d H:i:s');
                    $u->save();

                    $user_updated++;

                    // Format change message
                    $changeSummary = implode('', $changes);
                    $discordMessage = "### User Updated: {$u->FullName('FLC')}\n __Changes:__ {$changeSummary}";

                    // Send to Discord
                    $discord = new DiscordClient();
                    $discord->sendMessage($thread_id, $discordMessage);

                } else {
                    $user_not_updated++;
                }        

                $users_counted++;
                sleep(6.1);
            
            } catch (\GuzzleHttp\Exception\ClientException | \GuzzleHttp\Exception\ServerException $e) {
                $users_counted++;
                $statusCode = $e->getResponse()->getStatusCode();

                // Account VATSIM GDPR'd - Lets Remove the VITAL information
                if ($statusCode === 404) {
                    // Handle 404 differently (e.g., update account details)
                    // For example:
                    $u->update([
                        'fname' => 'VATSIM GDPR',
                        'lname' => 'Acc. Deleted',
                        'email' => 'deletedacc@ganderoceanic.ca',
                        'rating_id' => '-1',
                        'rating_short' => 'INAC',
                        'rating_long' => 'Inactive (GDPR Del.)',
                        'region_code' => null,
                        'region_name' => null,
                        'division_code' => null,
                        'division_name' => null,
                        'subdivision_code' => null,
                        'subdivision_name' => null,
                        'remember_token' => null,
                        'display_cid_only' => 0,
                        'display_fname' => 'VATSIM GDPR',
                        'display_last_name' => 1,
                        'discord_user_id' => null,
                        'discord_dm_channel_id' => null,
                        'avatar_mode' => 0,
                        'discord_username' => null,
                        'discord_avatar' => null,
                        'pilotrating_id' => null,
                        'pilotrating_short' => null,
                        'pilotrating_long' => null,
                        'militaryrating_id' => null,
                        'militaryrating_short' => null,
                        'militaryrating_long' => null,
                        'vatsim_gdpr_account' => 1,
                    ]);

                    $discord = new DiscordClient();
                    $discord->sendMessage($thread_id, 'VATSIM ACCOUNT DELETED (404): '.$u->FullName('FLC').' ('.$users_counted.'/'. $total_users.' users).');

                    $vatsim_gdpr++;
                    sleep(6.1);
                    continue;
                }

                // Otherwise, its a general error - lets log it
                $discord = new DiscordClient();
                $discord->sendMessage($thread_id, 'UPDATE FAILED CLIENT/SERVER: '.$u->FullName('FLC').' ('.$users_counted.'/'. $total_users.' users).');
            
                $vatsim_api_failed++;
                sleep(6.1);
                continue;
            } catch (\Exception $e) {
                $users_counted++;
                $discord = new DiscordClient();
                $discord->sendMessage($thread_id, 'UPDATE FAILED EXCEPTION: '.$u->FullName('FLC').' ('.$users_counted.'/'. $total_users.' users).');
            
                $vatsim_api_failed++;
                sleep(6.1);
                continue;
            }
            
        }

        // Record Information for Discord
        // Beginning
        $update_content = "Weekly User DB Updates were just completed";

        $update_content .= "\n\n **__User Updates:__**";
        $update_content .= "\n- Successful: ".$user_updated;
        if($update_rating > 0 ){
            $update_content .= "\n  - General Rating: ".$update_rating;
        }
        if($update_pilot > 0 ){
            $update_content .= "\n  - Pilot Rating: ".$update_pilot;
        }
        if($update_military > 0 ){
            $update_content .= "\n  - Mil. Rating: ".$update_military;
        }
        if($update_region > 0 ){
            $update_content .= "\n  - Region: ".$update_region;
        }
        if($update_division > 0 ){
            $update_content .= "\n  - Division: ".$update_division;
        }
        if($update_subdivision > 0 ){
            $update_content .= "\n  - SubDivision: ".$update_subdivision;
        }
        if($vatsim_gdpr > 0){
            $update_content .= "\n- VATSIM GDPR: ".$vatsim_gdpr;
        }
        if($vatsim_c1_upgrade > 0){
            $update_content .= "\n- New C1 Email: ".$vatsim_c1_upgrade;
        }
        if($vatsim_api_failed > 0){
            $update_content .= "\n- Failed: ".$vatsim_api_failed;
        }
        $update_content .= "\n- Not Required: ".$user_not_updated;


        // Completion Time
        $end_time = Carbon::now();
        $update_content .= "\n\n**__Script Time:__**";
        $update_content .= "\n- Script Time: " . $start_time->diffForHumans($end_time, ['parts' => 2, 'short' => true, 'syntax' => Carbon::DIFF_ABSOLUTE]) . ".";

        $discord = new DiscordClient();
        $discord->sendMessageWithEmbed(env('DISCORD_SERVER_LOGS'), 'WEEKLY: Mass User Updates', $update_content);

        $discord = new DiscordClient();
        $discord->sendMessage($thread_id, '# System User Update End');
    }

}
