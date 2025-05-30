<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Network\ExternalController;
use App\Models\Roster\RosterMember;
use App\Models\Users\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Services\DiscordClient;
use Carbon\Carbon;

class ProcessExternalControllers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Can run for 1hr
    public $timeout = 3600;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //  Delay the job retry to 5 minutes.
    public function backoff()
    {
        return [300];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
            // Can run for 20mins
            ini_set('max_execution_time', 3600);

            // Guzzle Client Initialization
            $guzzle = new Client(['timeout' => 100, 'connect_timeout' => 55]);

            // VATSIM Region List
            $vatsim_region_pull = $guzzle->request('GET', 'https://api.vatsim.net/api/regions');
            $vatsim_regions = json_decode($vatsim_region_pull->getBody(), true);

            // VATSIM Division List
            $vatsim_division_pull = $guzzle->request('GET', 'https://api.vatsim.net/api/divisions');
            $vatsim_divisions = json_decode($vatsim_division_pull->getBody(), true);

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

            // Script Start Time
            $start_time = Carbon::now();

            // Get Shanwick API Data         
            $eggx_api = $guzzle->request('GET', "https://www.vatsim.uk/api/validations?position=EGGX_CTR");
            $eggx_api_json = json_decode($eggx_api->getBody(), true);
            $eggx_json = $eggx_api_json['validated_members'];

            // Get New York API Data         
            $zny_api = $guzzle->request('GET', "https://nyartcc.org/api/controller/roster/oceanic");
            $zny_json = json_decode($zny_api->getBody(), true);
            

            // Get all IDs for Controllers on Gander Roster
            $non_czqo = [];
            $czqo = RosterMember::all()->pluck('user_id');
            $czqo = $czqo->toArray();
            
            // Check if Shanwick Roster has any duplicates
            foreach($eggx_json as $eggx){
                if (!in_array($eggx['id'], $czqo)) {
                    $non_czqo[] = [
                        'id' => $eggx['id'],
                        'visiting_origin' => 'eggx', //Shanwick Visitor
                    ];
                }
            }

            // Check if New York Roster has any duplicates
            foreach($zny_json as $zny){
                if (!in_array($zny['cid'], $czqo) && $zny['cid'] !== 7) {
                    $non_czqo[] = [
                        'id' => $zny['cid'],
                        'visiting_origin' => 'zny', //New York Visitor
                    ];
                }
            }

            // Set all Roster Validations to Null for Check Below
            $roster_total = ExternalController::all();

            foreach($roster_total as $rt){
                $rt->valid_during_update = null;
                $rt->save();
            }


            // Its now time. Lets go through each User that has been found from both APIs and update them!
            foreach($non_czqo as $member){

                $roster = ExternalController::find($member['id']);
                $user = User::find($member['id']);

                if($roster !== null){

                    // Roster Member Exists - Lets Update the Content
                    if($user !== null){
                        // Update based off of User Information
                        $roster->name = $user->FullName('FL');
                        $roster->rating = $user->rating_short;
                        $roster->region_code = $user->region_code;
                        $roster->region_name = $user->region_name;
                        $roster->division_code = $user->division_code;
                        $roster->division_name = $user->division_name;

                        // Check System Roles once per week by reassigning the role
                        if (Carbon::now()->dayOfWeek === Carbon::SATURDAY && Carbon::now()->hour === 14) {
                            $user->assignRole('Certified Controller');
                            $user->removeRole('Guest');
                        }

                    } else {
                        
                        if (Carbon::now()->dayOfWeek === Carbon::MONDAY && Carbon::now()->hour === 4) {
                            // Update Data from VATSIM Once Per Week
                            sleep(6);

                            // User Information Does Not Exist
                            $vatsim_data = $guzzle->request('GET', 'https://api.vatsim.net/v2/members/'.$member['id']);
                            $vatsim = json_decode($vatsim_data->getBody(), true);

                            // Match Info to the VATSIM Array
                            $region = array_filter($vatsim_regions, function ($r) use ($vatsim) {
                                return $r['id'] === $vatsim['region_id'];
                            });
                            $region = reset($region);
                            $division = array_filter($vatsim_divisions, function ($d) use ($vatsim) {
                                return $d['id'] === $vatsim['division_id'];
                            });
                            $division = reset($division);
                            $rating = array_filter($vatsim_ratings, function ($s) use ($vatsim) {
                                return $s['id'] === $vatsim['rating'];
                            });
                            $rating = reset($rating);

                            $roster->name = $member['id'];
                            $roster->rating = $rating ? $rating['short'] : 'Unknown';
                            $roster->region_code = $vatsim['region_id'];
                            $roster->region_name = $region ? $region['name'] : 'Unknown';
                            $roster->division_code = $vatsim['division_id'];
                            $roster->division_name = $division ? $division['name'] : 'Unknown';
                        }
                    }

                    $roster->valid_during_update = 1;
                    $roster->save();

                } else {

                    // Roster Member Does Not Exist - Lets Create a new Entry
                    $roster = New ExternalController;
                    $roster->id = $member['id'];
                    
                    if($user !== null){
                        // User Entry Exists - Add stuff from their Database1
                        $roster->name = $user->FullName('FL');
                        $roster->rating = $user->rating_short;
                        $roster->region_code = $user->region_code;
                        $roster->region_name = $user->region_name;
                        $roster->division_code = $user->division_code;
                        $roster->division_name = $user->division_name;

                        // Assign System Role to User
                        $user->assignRole('Certified Controller');
                        $user->removeRole('Guest');

                    } else {
                        sleep(6);

                        // User Information Does Not Exist
                        $vatsim_data = $guzzle->request('GET', 'https://api.vatsim.net/v2/members/'.$member['id']);
                        $vatsim = json_decode($vatsim_data->getBody(), true);

                        // Match Info to the VATSIM Array
                        $region = array_filter($vatsim_regions, function ($r) use ($vatsim) {
                            return $r['id'] === $vatsim['region_id'];
                        });
                        $region = reset($region);
                        $division = array_filter($vatsim_divisions, function ($d) use ($vatsim) {
                            return $d['id'] === $vatsim['division_id'];
                        });
                        $division = reset($division);
                        $rating = array_filter($vatsim_ratings, function ($s) use ($vatsim) {
                            return $s['id'] === $vatsim['rating'];
                        });
                        $rating = reset($rating);

                        $roster->name = $member['id'];
                        $roster->rating = $rating ? $rating['short'] : 'Unknown';
                        $roster->region_code = $vatsim['region_id'];
                        $roster->region_name = $region ? $region['name'] : 'Unknown';
                        $roster->division_code = $vatsim['division_id'];
                        $roster->division_name = $division ? $division['name'] : 'Unknown';
                    }
                    
                    
                    $roster->visiting_origin = $member['visiting_origin'];
                    $roster->valid_during_update = 1;
                    $roster->save();
                }
            }



            // Delete all Non-Existant Users from the Roster
            $old_members = ExternalController::whereNull('valid_during_update')->get();
            foreach($old_members as $om){
                $user = User::find($om->id);

                // Remove Certified Controller from User Information
                if($user !== null){
                    $user->removeRole('Certified Controller');
                    $user->assignRole('Guest');
                }

                $om->delete();
            }


            // Discord Message
            ## DISCORD UPDATE
        // {
        //     $discord = new DiscordClient;
        //     // Beginning
        //     $update_content = "External Controller Updates Completed";

            
        //     $end_time = Carbon::now();
        //     $update_content .= "\n\n**__Completion Time:__**";
        //     $update_content .= "\n- Script Time: " . $start_time->diffForHumans($end_time, ['parts' => 2, 'short' => true, 'syntax' => Carbon::DIFF_ABSOLUTE]) . ".";

        //     // Send Message
        //     $discord->sendMessageWithEmbed('1299248165551210506', 'HOURLY: External Controller Roster Updates', $update_content);
            
        // }
    }
}
