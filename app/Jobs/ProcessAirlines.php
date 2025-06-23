<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

class ProcessAirlines implements ShouldQueue
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
    $url = 'https://raw.githubusercontent.com/jpatokal/openflights/master/data/airlines.dat';

    $response = Http::get($url);

    if (!$response->successful()) {
        Log::error("Failed to fetch airlines.dat: " . $response->status());
        return;
    }

    // Each line in the body corresponds to a new record
    $lines = preg_split("/\r\n|\n|\r/", $response->body());

    $batch = [];
    $batchSize = 500;

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '') continue;

        $data = str_getcsv($line); // Safe for quoted strings

        $record = [
            'name' => $data[1] !== '\n' || $data[1] !== '' ? $data[1] : null,
            'iata' => $data[3] !== '\n' || $data[3] !== '' ? $data[3] : null,
            'icao' => $data[4] !== '\n' || $data[4] !== '' ? $data[4] : null,
        ];

        $batch[] = $record;

        if (count($batch) >= $batchSize) {
            $this->insertOrUpdateBatch($batch);
            $batch = [];
        }
    }

    if (!empty($batch)) {
        $this->insertOrUpdateBatch($batch);
    }

    Log::info("Airlines import completed.");
    }

    protected function insertOrUpdateBatch(array $batch)
    {
        foreach ($batch as $entry) {
            DB::table('flight_airlines')->updateOrInsert(
                ['icao' => $entry['icao']],
                $entry
            );
        }
    }

}
