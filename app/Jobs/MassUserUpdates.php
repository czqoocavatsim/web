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

    public $timeout = 60000;

    /**
     * Execute the job.
     *
     * @return void
     */

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
        $vatsim_api_failed = 0;

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

        // Get full User list
        $user = User::all();
        $total_users = count($user);
        $users_counted = 0;

        foreach($user as $u){

            // Ignore the following IDs (not actually members)
            if($u->id == 1 || $u->id == 2){
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
                }
            
                // Lets Update the User Data based off the API Return
                $needsUpdate = false;

                // Compare each attribute
                if ($u->rating_id != $vatsim['rating']) $needsUpdate = true;
                if ($u->rating_short != $rating['short']) $needsUpdate = true;
                if ($u->rating_long != $rating['long']) $needsUpdate = true;
                if ($u->rating_GRP != $rating['long']) $needsUpdate = true;
                if ($u->pilotrating_id != $vatsim['pilotrating']) $needsUpdate = true;
                if ($u->pilotrating_short != $pilotratingshortname) $needsUpdate = true;
                if ($u->pilotrating_long != $pilotratinglongname) $needsUpdate = true;
                if ($u->reg_date != Carbon::parse($vatsim['reg_date'])->format('Y-m-d H:i:s')) $needsUpdate = true;
                if ($u->region_code != $vatsim['region_id']) $needsUpdate = true;
                if ($u->region_name != $region['name']) $needsUpdate = true;
                if ($u->division_code != $vatsim['division_id']) $needsUpdate = true;
                if ($u->division_name != $division['name']) $needsUpdate = true;
                if ($u->subdivision_code != $vatsim['subdivision_id']) $needsUpdate = true;
                if ($u->subdivision_name != $subdivisionFullname) $needsUpdate = true;

                if ($needsUpdate) {
                    $u->rating_id = $vatsim['rating'];
                    $u->rating_short = $rating['short'];
                    $u->rating_long = $rating['long'];
                    $u->rating_GRP = $rating['long'];
                    $u->pilotrating_id = $vatsim['pilotrating'];
                    $u->pilotrating_short = $pilotratingshortname;
                    $u->pilotrating_long = $pilotratinglongname;
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
                } else {
                    $user_not_updated++;
                }        

                $users_counted++;
                // $discord = new DiscordClient();
                // $discord->sendMessage('1343024277225607208', 'UPDATE COMPLETED: '.$u->FullName('FLC').' ('.$users_counted.'/'. $total_users.' users).');
              
                sleep(7);
            
            } catch (\GuzzleHttp\Exception\ClientException | \GuzzleHttp\Exception\ServerException $e) {
                $users_counted++;
                $discord = new DiscordClient();
                $discord->sendMessage('1343024277225607208', 'UPDATE FAILED CLIENT/SERVER: '.$u->FullName('FLC').' ('.$users_counted.'/'. $total_users.' users).');
            
                $vatsim_api_failed++;
                sleep(7);
                continue;
            } catch (\Exception $e) {
                $users_counted++;
                $discord = new DiscordClient();
                $discord->sendMessage('1343024277225607208', 'UPDATE FAILED EXCEPTION: '.$u->FullName('FLC').' ('.$users_counted.'/'. $total_users.' users).');
            
                $vatsim_api_failed++;
                sleep(7);
                continue;
            }
            
        }

        // Record Information for Discord
        // Beginning
        $update_content = "Quarterly User Database Updates were just completed";

        $update_content .= "\n\n **__User Updates:__**";
        $update_content .= "\n- No Update Required: ".$user_not_updated;
        $update_content .= "\n- Successful: ".$user_updated;
        $update_content .= "\n- Failed: ".$vatsim_api_failed;


        // Completion Time
        $end_time = Carbon::now();
        $update_content .= "\n\n**__Script Time:__**";
        $update_content .= "\n- Script Time: " . $start_time->diffForHumans($end_time, ['parts' => 2, 'short' => true, 'syntax' => Carbon::DIFF_ABSOLUTE]) . ".";

        $discord = new DiscordClient();
        $discord->sendMessageWithEmbed('1297573259663118368', 'WEEKLY: Mass User Updates', $update_content);
    }

}
