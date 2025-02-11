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

        // Discord Bot Variable Initialisation
        $start_time = Carbon::now();
        $user_updated = 0;
        $vatsim_api_failed = 0;

        // VATSIM Region List
        $vatsim_region_pull = $guzzle->request('GET', 'https://api.vatsim.net/api/regions');
        $vatsim_regions = json_decode($vatsim_region_pull->getBody(), true);

        // VATSIM Division List
        $vatsim_division_pull = $guzzle->request('GET', 'https://api.vatsim.net/api/divisions');
        $vatsim_divisions = json_decode($vatsim_division_pull->getBody(), true);

        // VATSIM Division List
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

        // Get full User list
        $user = User::all();

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
                }
            
                // Lets Update the User Data based off the API Return
                $u->rating_id = $vatsim['rating'];
                $u->rating_short = $rating['short'];
                $u->rating_long = $rating['long'];
                $u->rating_GRP = $rating['long'];
                $u->reg_date = Carbon::parse($vatsim['reg_date'])->format('Y-m-d H:i:s');
                $u->region_code = $vatsim['region_id'];
                $u->region_name = $region['name'];
                $u->division_code = $vatsim['division_id'];
                $u->division_name = $division['name'];
                $u->subdivision_code = $vatsim['subdivision_id'];
                $u->subdivision_name = $subdivisionFullname;
                $u->updated_at = Carbon::now()->format('Y-m-d H:i:s');
                $u->save();
            
                // Add One to Successful User Update
                $user_updated++;
            
                sleep(7);
            
            } catch (\GuzzleHttp\Exception\ClientException | \GuzzleHttp\Exception\ServerException $e) {
                $vatsim_api_failed++;
                sleep(7);
                continue;
            } catch (\Exception $e) {
                // Catch any other unexpected errors
                $vatsim_api_failed++;
                sleep(7);
                continue;
            }
            
        }

        // Record Information for Discord
        // Beginning
        $update_content = "Quarterly User Database Updates were just completed";

        $update_content .= "\n\n **__Information:__**";
        $update_content .= "\n- Successful Updates: ".$user_updated;
        $update_content .= "\n- Failed Updates: ".$vatsim_api_failed;


        // Completion Time
        $end_time = Carbon::now();
        $update_content .= "\n\n**__Script Time:__**";
        $update_content .= "\n- Script Time: " . $start_time->diffForHumans($end_time, ['parts' => 2, 'short' => true, 'syntax' => Carbon::DIFF_ABSOLUTE]) . ".";

        $discord = new DiscordClient();
        $discord->sendMessageWithEmbed('1338045308835463293', 'QUARTERLY: Mass User Updates', $update_content);
    }

}