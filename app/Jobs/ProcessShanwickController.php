<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Network\ShanwickController;
use App\Models\Roster\RosterMember;
use App\Models\Users\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ProcessShanwickController implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
            // Data to Start Off With
            ini_set('max_execution_time', 300);
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

            // Create a Request for the Shanwick Controllers
            $client = new Client(['timeout' => 250]);
            $response = $client->request('GET', 'https://www.vatsim.uk/api/validations?position=EGGX_FSS');
            $data = json_decode($response->getBody(), true);
            $data_json = $data['validated_members'];
            

            // Figure out which ones are already on the roster
            $non_czqo = [];
            $czqo = RosterMember::all()->pluck('user_id');
            $czqo = $czqo->toArray();
            
            foreach($data_json as $eggx){
                if (!in_array($eggx['id'], $czqo)) {
                    // If not, add the member's ID to the non_roster_members array
                    $non_czqo[] = $eggx['id'];
                }
            }

            // Delete all Roster Controllers before the update
            $shanwick_table = ShanwickController::all();
            foreach($shanwick_table as $st){
                $st->delete();
            }

            $controller = [];
            foreach($non_czqo as $eggx){

                $czqo_user = [];
                $czqo_user = User::find($eggx);

                if($czqo_user !== null){

                    // to add additional details for members apart of CZQO. TBC

                } else {
                    // User not in CZQO DB. Get Data from VATSIM.
                    $client2 = new Client();
                    $response2 = $client2->request('GET', 'https://api.vatsim.net/v2/members/'.$eggx);
                    $data_controller = json_decode($response2->getBody(), true);
                    
                    foreach($vatsim_ratings as $rating){
                        if($rating['id'] == $data_controller['rating']){
                            $rating_id = $rating['short'];
                        }
                    }

                    ShanwickController::create([
                        'controller_cid' => $eggx,
                        'name' => $eggx,
                        'rating' => $rating_id,
                        'division' => $data_controller['division_id'],
                    ]);
                }

                
            }

            dd($controller);
    }
}
