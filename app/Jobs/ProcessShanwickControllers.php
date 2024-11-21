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
use Carbon\Carbon;

class ProcessShanwickControllers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600;

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
            ini_set('max_execution_time', 600);

            // VATSIM Region List
            $vatsim_regions = [
                    [
                    "id" => "AMAS",
                    "name" => "Americas",
                    "director" => "1013441"
                    ],
                    [
                    "id" => "APAC",
                    "name" => "Asia Pacific",
                    "director" => "901134"
                    ],
                    [
                    "id" => "EMEA",
                    "name" => "Europe, Middle East and Africa",
                    "director" => "858680"
                    ],
                ];

                // VATSIM Division List
            $vatsim_divisions = [
                [
                "id" => "BRZ",
                "name" => "Brazil (VATBRZ)",
                "parentregion" => "AMAS",
                "subdivisionallowed" => 0
                ],
                [
                "id" => "CAM",
                "name" => "Central America",
                "parentregion" => "AMAS",
                "subdivisionallowed" => 0
                ],
                [
                "id" => "CAN",
                "name" => "Canada",
                "parentregion" => "AMAS",
                "subdivisionallowed" => 1
                ],
                [
                "id" => "CAR",
                "name" => "Caribbean",
                "parentregion" => "AMAS",
                "subdivisionallowed" => 1
                ],
                [
                "id" => "EUD",
                "name" => "Europe (except UK)",
                "parentregion" => "EMEA",
                "subdivisionallowed" => 1
                ],
                [
                "id" => "GBR",
                "name" => "United Kingdom",
                "parentregion" => "EMEA",
                "subdivisionallowed" => 0
                ],
                [
                "id" => "IL",
                "name" => "Israel (VATIL)",
                "parentregion" => "EMEA",
                "subdivisionallowed" => 0
                ],
                [
                "id" => "JPN",
                "name" => "Japan",
                "parentregion" => "APAC",
                "subdivisionallowed" => 0
                ],
                [
                "id" => "KOR",
                "name" => "Korea",
                "parentregion" => "APAC",
                "subdivisionallowed" => 0
                ],
                [
                "id" => "MCO",
                "name" => "Mexico",
                "parentregion" => "AMAS",
                "subdivisionallowed" => 0
                ],
                [
                "id" => "MENA",
                "name" => "Middle East and North Africa",
                "parentregion" => "EMEA",
                "subdivisionallowed" => 1
                ],
                [
                "id" => "NZ",
                "name" => "New Zealand (VATNZ)",
                "parentregion" => "APAC",
                "subdivisionallowed" => 0
                ],
                [
                "id" => "PAC",
                "name" => "Australia (VATPAC)",
                "parentregion" => "APAC",
                "subdivisionallowed" => 0
                ],
                [
                "id" => "PRC",
                "name" => "People's Republic of China",
                "parentregion" => "APAC",
                "subdivisionallowed" => 0
                ],
                [
                "id" => "ROC",
                "name" => "Republic of China (Taiwan)",
                "parentregion" => "APAC",
                "subdivisionallowed" => 0
                ],
                [
                "id" => "RUS",
                "name" => "Russia",
                "parentregion" => "EMEA",
                "subdivisionallowed" => 0
                ],
                [
                "id" => "SAM",
                "name" => "South America",
                "parentregion" => "AMAS",
                "subdivisionallowed" => 1
                ],
                [
                "id" => "SEA",
                "name" => "Southeast Asia (VATSEA)",
                "parentregion" => "APAC",
                "subdivisionallowed" => 1
                ],
                [
                "id" => "SSA",
                "name" => "Sub-Sahara Africa",
                "parentregion" => "EMEA",
                "subdivisionallowed" => 1
                ],
                [
                "id" => "USA",
                "name" => "United States",
                "parentregion" => "AMAS",
                "subdivisionallowed" => 0
                ],
                [
                "id" => "WA",
                "name" => "West Asia",
                "parentregion" => "APAC",
                "subdivisionallowed" => 1
                ]
            ];

            // VATSIM Ratings
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
            $client = new Client(['timeout' => 1000, 'connect_timeout' => 1000]);
            $URL = "https://www.vatsim.uk/api/validations?position=EGGX_CTR";
            $response = $client->request('GET', $URL);
            $data = json_decode($response->getBody(), true);
            $data_json = $data['validated_members'];
            

            // Figure out which ones are already on the roster, and ignore them.
            $non_czqo = [];
            $czqo = RosterMember::all()->pluck('user_id');
            $czqo = $czqo->toArray();
            
            foreach($data_json as $eggx){
                if (!in_array($eggx['id'], $czqo)) {
                    // If not, add the member's ID to the non_roster_members array
                    $non_czqo[] = $eggx['id'];
                }
            }

            // dd($non_czqo);

            // Loop through all Non-CZQO Roster Members, and gather DB info from CZQO for those who have it.
            $get_vatsim_data = [];
            $use_gander_data = [];
            foreach($non_czqo as $eggx_controller){
                $controller = User::where('id', $eggx_controller)->first();
            
                if($controller !== null){
                    // Controller ID is in Gander DB
                    $use_gander_data[] = $eggx_controller;
                    // dd($controller); // Output the controller for debugging
                } else {
                    // Controller has never logged into Gander
                    $get_vatsim_data[] = $eggx_controller;
                }
            }

            // dd($get_vatsim_data);
            // dd($use_gander_data);

            // Get Current Shanwick Details
            $shanwick_data = ShanwickController::all()->pluck('controller_cid');
            $shanwick_data = $shanwick_data->toArray();
            $delete_from_eggx_controllers = array_diff($shanwick_data, $non_czqo);


            // Update or Create Gander Shanwick Roster
            // Update from CZQO DB
            foreach($use_gander_data as $data1){
                $gander_controller = User::where('id', $data1)->first();

                if($gander_controller !== null){
                    ShanwickController::UpdateorCreate([
                        'controller_cid' => $data1,
                        'name' => $gander_controller->fullName('FLC'),
                        'rating' => $gander_controller->rating_short,
                        'division' => $gander_controller->division_code,
                        'division_name' => $gander_controller->division_name,
                        'region_code' => $gander_controller->region_code,
                        'region_name' => $gander_controller->region_name,
                    ]);
                }
            }

            // // Update from VATSIM API
            // if (Carbon::now()->isMonday()) {
            //     foreach($get_vatsim_data as $data2){
            //         // User not in CZQO DB. Get Data from VATSIM.
            //         $client2 = new Client(['timeout' => 1000]);
            //         $response2 = $client2->request('GET', 'https://api.vatsim.net/v2/members/'.$data2);
            //         $data_controller = json_decode($response2->getBody(), true);
                    
            //         foreach($vatsim_ratings as $rating){
            //             if($rating['id'] == $data_controller['rating']){
            //                 $rating_id = $rating['short'];
            //             }
            //         }

            //         foreach($vatsim_divisions as $division){
            //             if($division['id']= $data_controller['division_id']){
            //                 $division_code = $division['id'];
            //                 $division_name = $division['name'];
            //             }
            //         }

            //         foreach($vatsim_regions as $region){
            //             if($region['id']= $data_controller['region_id']){
            //                 $region_code = $region['id'];
            //                 $region_name = $region['name'];
            //             }
            //         }

            //         ShanwickController::UpdateorCreate([
            //             'controller_cid' => $data2,
            //             'name' => $data2,
            //             'rating' => $rating_id,
            //             'division' => $division_code,
            //             'division_name' => null,
            //             'region_code' => $region_code,
            //             'region_name' => null,
            //         ]);
            //     }
            // }

            // Delete following CIDs from DB
            $shanwick_table = ShanwickController::all();
            $shanwick_table = $shanwick_table->toArray();
            
            foreach($delete_from_eggx_controllers as $data3){
                foreach($shanwick_table as $st){
                    if($st['controller_cid'] == $data3){
                        $shanwick_data = ShanwickController::where('controller_cid', $data3)->get();

                        foreach ($shanwick_data as $data) {
                            $data->delete(); // Delete each person not supposed to be on the roster
                        }
                    }
                }
            }
    }
}
