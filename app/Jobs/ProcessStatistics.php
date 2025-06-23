<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Statistics\FlightLog;
use App\Models\Statistics\ControllerStats;
use App\Models\Statistics\PilotStats;
use App\Models\Statistics\AirlineStats;
use App\Models\Statistics\LevelStats;
use App\Models\Statistics\AircraftStats;
use App\Models\Statistics\AirportStats;
use App\Models\Statistics\AirportPairStats;

use App\Models\Network\ExternalController;
use App\Models\Roster\RosterMember;

use App\Services\VATSIMClient;
use App\Services\DiscordClient;

class ProcessStatistics implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        # Waves with pure anxiety // Statistics Calculator. Operates once per hour

        // Lets get the Flight Stats to run through so many times it isnt even funny anymore
        $monthFlights = FlightLog::whereNotNull('save_details')->whereBetween('exited_oca', [Carbon::now()->startOfMonth(), Carbon::now()])->get();
        $yearFlights = FlightLog::whereNotNull('save_details')->whereBetween('exited_oca', [Carbon::now()->startOfYear(), Carbon::now()])->get();

        ### End of Month - Move Current > Last Month Stats
        {
            if (Carbon::now()->isSameDay(Carbon::now()->startOfMonth()) && Carbon::now()->format('H') === '00') {
                $controller_stats = ControllerStats::all();
                $pilot_stats = PilotStats::all();
                $airline_stats = AirlineStats::all();
                $aircraft_stats = AircraftStats::all();
                $level_stats = LevelStats::all();
                $airport_stats = AirportStats::all();
                $airport_pair_stats = AirportPairStats::all();

                foreach($controller_stats as $cs){
                    $cs->update([
                        'current' => 0,
                        'last_month' => $cs->current,
                    ]);
                }

                foreach($pilot_stats as $cs){
                    $cs->update([
                        'current' => 0,
                        'last_month' => $cs->current,
                    ]);
                }

                foreach($airline_stats as $cs){
                    $cs->update([
                        'current' => 0,
                        'last_month' => $cs->current,
                    ]);
                }

                foreach($aircraft_stats as $cs){
                    $cs->update([
                        'current' => 0,
                        'last_month' => $cs->current,
                    ]);
                }

                foreach($level_stats as $cs){
                    $cs->update([
                        'current' => 0,
                        'last_month' => $cs->current,
                    ]);
                }

                foreach($airport_stats as $cs){
                    $cs->update([
                        'current_dep' => 0,
                        'current_arr' => 0,
                        'last_month_dep' => $cs->current_dep,
                        'last_month_arr' => $cs->current_arr,
                    ]);
                }

                foreach($airport_pair_stats as $cs){
                    $cs->update([
                        'current' => 0,
                        'last_month' => $cs->current,
                    ]);
                }
            }
        }

        ### CONTROLLER STATISTICS
        // Controller Statistics
        {
            //Top Month Controllers
            $ganderController = RosterMember::all();
            $partnerController = ExternalController::all();
            $partnerController = $partnerController->map(function ($item) {
                $item->cid = $item->id; // Copy `id` into a new `cid` attribute
                return $item;
            });
            $topControllers = $ganderController->merge($partnerController);

           foreach($topControllers as $controllers){
                if($controllers->currency > 0 || $controllers->monthly_hours > 0){
                    ControllerStats::updateOrCreate(
                        ['cid' => $controllers->cid],
                        ['year' => $controllers->currency,
                        'current' => $controllers->monthly_hours,
                        'visiting_origin' => $controllers->visiting_origin ?? null]
                    );
                }
           }
        }
        
    
        ### PILOT STATISTICS
        // Flight Statistics
        {
            $flights_array = [];

            foreach($monthFlights as $flights){
                $cid = $flights->cid;

                if (!isset($flights_array[$cid])) {
                    $flights_array[$cid] = [
                        'cid' => $cid,
                        'month' => 1,
                        'year' => 0,
                    ];
                } else {
                    $flights_array[$cid]['month']++;
                }
            }

            foreach($yearFlights as $flights){
                $cid = $flights->cid;

                if (!isset($flights_array[$cid])) {
                    $flights_array[$cid] = [
                        'cid' => $cid,
                        'month' => 0,
                        'year' => 1,
                    ];
                } else {
                    $flights_array[$cid]['year']++;
                }
            }

            // Update Pilot Stats
            foreach($flights_array as $flight){
                if($flight['month'] > 0 || $flight['year'] > 0){
                    PilotStats::updateOrCreate(
                        ['cid' => $flight['cid']],
                        ['year' => $flight['year'],
                        'current' => $flight['month'],
                    ],
                    );
                }
           }
        }

        
        ### AIRCRAFT STATISTICS
        // Aircraft Types Statistics
        {
            $aircraft_array = [];

            // Calculation for month Flights
            foreach($monthFlights as $aircraft){
                $ac = $aircraft->aircraft;

                if (!isset($aircraft_array[$ac])) {
                    $aircraft_array[$ac] = [
                        'ac' => $ac,
                        'month' => 1,
                        'year' => 0,
                    ];
                } else {
                    $aircraft_array[$ac]['month']++;
                }
            }

            // Calculation for year Aircraft
            foreach($yearFlights as $aircraft){
                $ac = $aircraft->aircraft;

                if (!isset($aircraft_array[$ac])) {
                    $aircraft_array[$ac] = [
                        'ac' => $ac,
                        'month' => 0,
                        'year' => 1,
                    ];
                } else {
                    $aircraft_array[$ac]['year']++;
                }
            }

            // Update FL Stats
            foreach($aircraft_array as $ac){
                if($ac['month'] > 0 || $ac['year'] > 0){
                    AircraftStats::updateOrCreate(
                        ['code' => $ac['ac']],
                        ['year' => $ac['year'],
                        'current' => $ac['month'],
                    ],
                    );
                }
           }
        }

        // Airline Statistics
        {
            $airline_array = [];


            // Calculation for month Flights
            foreach($monthFlights as $airline){
                if($airline->airline == null){continue;};

                $ac = $airline->airline;

                if (!isset($airline_array[$ac])) {
                    $airline_array[$ac] = [
                        'airline' => $ac,
                        'month' => 1,
                        'year' => 0,
                    ];
                } else {
                    $airline_array[$ac]['month']++;
                }
            }

            // Calculation for month Flights
            foreach($yearFlights as $airline){
                if($airline->airline == null){continue;};

                $ac = $airline->airline;

                if (!isset($airline_array[$ac])) {
                    $airline_array[$ac] = [
                        'airline' => $ac,
                        'month' => 0,
                        'year' => 1,
                    ];
                } else {
                    $airline_array[$ac]['year']++;
                }
            }

            // Update Airline Stats
            foreach($airline_array as $airline){
                if($airline['month'] > 0 || $airline['year'] > 0){
                    AirlineStats::updateOrCreate(
                        ['code' => $airline['airline']],
                        ['year' => $airline['year'],
                        'current' => $airline['month'],
                    ],
                    );
                }
           }


        }
        
        // Cruise Level Statistics
        {
            $levels_array = [];

            // Calculation for month Flights
            foreach($monthFlights as $levels){
                $level = $levels->fl;

                if (!isset($levels_array[$level])) {
                    $levels_array[$level] = [
                        'level' => $level,
                        'month' => 1,
                        'year' => 0,
                    ];
                } else {
                    $levels_array[$level]['month']++;
                }
            }

            // Calculation for Year Flights
            foreach($yearFlights as $levels){
                $level = $levels->fl;

                if (!isset($levels_array[$level])) {
                    $levels_array[$level] = [
                        'level' => $level,
                        'month' => 0,
                        'year' => 1,
                    ];
                } else {
                    $levels_array[$level]['year']++;
                }
            }

            // Update FL Stats
            foreach($levels_array as $levels){
                if($levels['month'] > 0 || $levels['year'] > 0){
                    LevelStats::updateOrCreate(
                        ['level' => $levels['level']],
                        ['year' => $levels['year'],
                        'current' => $levels['month'],
                    ],
                    );
                }
           }
        }
        
        
        ### AIRPORT STATISTICS
        // Departure & Arrival Statistics
        {
            $airport_array = [];

            // Calculation for month Airports
            foreach($monthFlights as $airport){
                $dep = $airport->dep;
                $arr = $airport->arr;

                if (!isset($airport_array[$dep])) {
                    $airport_array[$dep] = [
                        'airport' => $dep,
                        'month_dep' => 1,
                        'month_arr' => 0,
                        'year_dep' => 0,
                        'year_arr' => 0,
                    ];
                } else {
                    $airport_array[$dep]['month_dep']++;
                }

                if (!isset($airport_array[$arr])) {
                    $airport_array[$arr] = [
                        'airport' => $arr,
                        'month_dep' => 0,
                        'month_arr' => 1,
                        'year_dep' => 0,
                        'year_arr' => 0,
                    ];
                } else {
                    $airport_array[$arr]['month_arr']++;
                }
            }

            // Calculation for year Airports
            foreach($yearFlights as $airport){
                $dep = $airport->dep;
                $arr = $airport->arr;

                if (!isset($airport_array[$dep])) {
                    $airport_array[$dep] = [
                        'airport' => $dep,
                        'month_dep' => 0,
                        'month_arr' => 0,
                        'year_dep' => 1,
                        'year_arr' => 0,
                    ];
                } else {
                    $airport_array[$dep]['year_dep']++;
                }

                if (!isset($airport_array[$arr])) {
                    $airport_array[$arr] = [
                        'airport' => $arr,
                        'month_dep' => 0,
                        'month_arr' => 0,
                        'year_dep' => 0,
                        'year_arr' => 1,
                    ];
                } else {
                    $airport_array[$arr]['year_arr']++;
                }
            }

            foreach($airport_array as $ap){
                if($ap['month_dep'] > 0 || $ap['year_dep'] > 0 || $ap['month_arr'] > 0 || $ap['year_arr'] > 0){
                    AirportStats::updateOrCreate(
                        ['airport' => $ap['airport']],
                        ['current_dep' => $ap['month_dep'],
                        'current_arr' => $ap['month_arr'],
                        'year_dep' => $ap['year_dep'],
                        'year_arr' => $ap['year_arr']
                        ],
                    );
                }
           }
        }

        // City Pair Statistics
        {
            $airport_pairs = [];

            foreach ($monthFlights as $citypair) {
                $departure = $citypair->dep;
                $arrival = $citypair->arr;

                // Create bidirectional key (alphabetical order)
                $pair = collect([$departure, $arrival])->sort()->implode('_');
                [$airport1, $airport2] = explode('_', $pair);

                if (!isset($airport_pairs[$pair])) {
                    $airport_pairs[$pair] = [
                        'pair' => $pair,
                        'airport1' => $airport1,
                        'airport2' => $airport2,
                        'month' => 1,
                        'year' => 0,
                    ];
                } else {
                    $airport_pairs[$pair]['month']++;
                }
            }

            foreach ($yearFlights as $citypair) {
                $departure = $citypair->dep;
                $arrival = $citypair->arr;

                // Create bidirectional key (alphabetical order)
                $pair = collect([$departure, $arrival])->sort()->implode('_');
                [$airport1, $airport2] = explode('_', $pair);

                if (!isset($airport_pairs[$pair])) {
                    $airport_pairs[$pair] = [
                        'pair' => $pair,
                        'airport1' => $airport1,
                        'airport2' => $airport2,
                        'month' => 0,
                        'year' => 1,
                    ];
                } else {
                    $airport_pairs[$pair]['year']++;
                }
            }

            foreach($airport_pairs as $ap){
                    if($ap['month'] > 0 || $ap['year'] > 0){
                        AirportPairStats::updateOrCreate(
                            ['airport1' => $ap['airport1'], 'airport2' => $ap['airport2']],
                            ['current' => $ap['month'],
                            'year' => $ap['year'],
                            ],
                        );
                    }
            }
        }
    }
}