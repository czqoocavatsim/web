<?php

namespace App\Console;

use App\AuditLogEntry;
use App\Models\AtcTraining\RosterMember;
use App\Models\Network\MonitoredPosition;
use App\Models\Network\SessionLog;
use App\User;
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
    protected function schedule (Schedule $schedule)
    {
        // Connection logging
        $schedule->call(function () {
            // Load VATSIM data
            $vatsim = new \Vatsimphp\VatsimData();
            $vatsim->loadData();

            // Active lists
            $onlineControllers = array();

            // Getters
            $positions = MonitoredPosition::all();
            $controllers = $vatsim->getControllers();

            // Scan controller list for callsign relationships
            foreach ($controllers as $controller) {
                foreach ($positions as $position) {
                    // Checker to determine whether it's a callsign or a prefix
                    $callsign = false;
                    if (!strpos($position->identifier, '_')) { // If it is a prefix
                        if (substr($controller['callsign'], 0, strlen($position->identifier)) == $position->identifier) {
                            array_push($onlineControllers, $controller); // Add to array if callsign starts with prefix
                        }
                    }
                    else { // If it's a callsign
                        if ($controller['callsign'] == $position->identifier) {
                            array_push($onlineControllers, $controller); // Add if the callsign is the same as the position identifier
                        }
                    }
                }
            }

            // List of session logs
            $sessionLogs =  SessionLog::all();

            // Check logs against currently online controllers
            foreach ($onlineControllers as $oc) {
                $matchFound = false;
                foreach ($sessionLogs as $log) {
                    // Parse logon time lol
                    $ocLogon = substr($oc['time_logon'],0,4).'-'
                        .substr($oc['time_logon'], 4, 2).'-'
                        .substr($oc['time_logon'], 6, 2).' '
                        .substr($oc['time_logon'], 8, 2).':'
                        .substr($oc['time_logon'], 10, 2).':'
                        .substr($oc['time_logon'], 12, 2);

                    // If a match is found
                    if ($ocLogon == $log->session_start) {
                        // todo: perform connection authentication again
                        $matchFound = true;
                    } else {
                        continue;
                    }
                }

                // If no match was found
                if (!$matchFound) {
                    // Build new session log
                    $sessionLog = new SessionLog();
                    $sessionLog->cid = $oc['cid'];
                    $sessionLog->session_start = $ocLogon;
                    $sessionLog->callsign = $oc['callsign'];
                    $sessionLog->isNew = true;

                    // Check the user's CID against the roster
                    $user = RosterMember::where('cid', '=', $oc['cid'])->first();
                    if ($user && ($user->status != 'Training')) { // Add if on roster, don't if not (big problem lmao)
                        $sessionLog->user_id = $user->id;
                    }
                    else {
                        // todo: send email to me here if user_id is null
                    }

                    // Add session
                    $sessionLog->save();
                }
            }

            // todo: do a search through the session logs to find any sessions without an end time (controller just logged off)
            foreach ($sessionLogs as $log) {

            }
        })->everyMinute();

        // Hour checking

        // Purge period
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
