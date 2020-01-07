<?php

namespace App\Console;

use App\AuditLogEntry;
use App\Models\AtcTraining\RosterMember;
use App\User;
use App\Models\Network\VatsimPosition;
use App\Models\Network\VatsimSession;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function (){
            Log::info('Running controller session detection...');
            //Record controller sessions
            $logFile = __DIR__.'/vendor/skymeyer/vatsimphp/app/logs/pilots.log';
            $vatsim = new \Vatsimphp\VatsimData();
            $vatsim->loadData();
            if (!$vatsim->loadData()) {
                Log::alert('No VATSIM data ('.Carbon::now().')');
            } else {
                $positions = VatsimPosition::all();
                $controllers = $vatsim->getControllers();
                foreach ($controllers as $controller)
                {
                    foreach ($positions as $position)
                    {
                        if ($controller['callsign'] == $position->callsign)
                        {
                            Log::info($controller['callsign'].' match!');
                            if (!RosterMember::where('cid', intval($controller['cid']))->first())
                            {
                                Log::info('Non rostered controller detected.');
                                $hook = json_encode([
                                    /*
                                     * The general "message" shown above your embeds
                                     */
                                    "content" => "<@&524435557472796686>",
                                    /*
                                     * The username shown in the message
                                     */
                                    "username" => "Gander Oceanic Core",
                                    /*
                                     * The image location for the senders image
                                     */
                                    "avatar_url" => asset('img/iconwhitebg.png'),
                                    /*
                                     * Whether or not to read the message in Text-to-speech
                                     */
                                    "tts" => false,
                                    /*
                                     * File contents to send to upload a file
                                     */
                                    // "file" => "",
                                    /*
                                     * An array of Embeds
                                     */
                                    "embeds" => [
                                        /*
                                         * Our first embed
                                         */
                                        [
                                            // Set the title for your embed
                                            "title" => 'Non rostered controller detected',

                                            // The type of your embed, will ALWAYS be "rich"
                                            "type" => "rich",

                                            // A description for your embed
                                            "description" => "",

                                            /* A timestamp to be displayed below the embed, IE for when an an article was posted
                                             * This must be formatted as ISO8601
                                             */
                                            "timestamp" => date('Y-m-d H:i:s'),

                                            // The integer color to be used on the left side of the embed
                                            "color" => hexdec( "2196f3" ),

                                            // Footer object
                                            "footer" => [
                                                "text" => "Gander Oceanic Core",
                                                "icon_url" => asset('img/iconwhitebg.png')
                                            ],

                                            // Field array of objects
                                            "fields" => [
                                                // Field 1
                                                [
                                                    "name" => "Callsign",
                                                    "value" => $position->callsign.' '.route('network.positions.view', $position->id),
                                                    "inline" => false
                                                ],
                                                // Field 2
                                                [
                                                    "name" => "CID",
                                                    "value" => $controller['cid'],
                                                    "inline" => false
                                                ],
                                                [
                                                    "name" => "Time",
                                                    "value" => Carbon::now()->toDayDateTimeString(),
                                                    "inline" => false
                                                ]
                                            ]
                                        ]
                                    ]

                                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

                                $ch = curl_init();
                                curl_setopt_array($ch, [
                                    CURLOPT_URL => config('discord.exec_webhook'),
                                    CURLOPT_POST => true,
                                    CURLOPT_POSTFIELDS => $hook,
                                    CURLOPT_HTTPHEADER => [
                                        'Length' => strlen($hook),
                                        "Content-Type: application/json"
                                    ]
                                ]);
                                $response = curl_exec($ch);
                                if (curl_errno($ch)) {
                                    $error = curl_error($ch);
                                    Log::error($error);
                                }
                                curl_close($ch);
                            }
                            else
                            {
                                Log::info('Controller rostered');
                            }
                        }
                    }
                }
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
