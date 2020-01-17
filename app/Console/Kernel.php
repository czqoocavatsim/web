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
            $sessionLogs =  SessionLog::where("session_end", null)->get();

            // Check logs against currently online controllers
            foreach ($onlineControllers as $oc) {
                $matchFound = false;
                $ocLogon = NULL;
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
                        if (!$log->roster_member_id) { // Check if they're naughty
                            if ($log->email_sent < 2) { // todo: send me email
                                // MailController->sendUnauthorisedMail(); (or something like that idk how you do it)
                            }
                        }
                        $matchFound = true;
                    } else {
                        continue; // No match was found
                    }
                }

                // Create log variable here so it's within appropriate scope
                $sessionLog = NULL;

                // If no match was found
                if (!$matchFound) {
                    // Build new session log
                    $sessionLog = new SessionLog();
                    $sessionLog->cid = $oc['cid'];
                    $sessionLog->session_start = $ocLogon;
                    $sessionLog->callsign = $oc['callsign'];
                    $sessionLog->isNew = true;
                    $sessionLog->emails_sent = 0;

                    // Check the user's CID against the roster
                    $user = RosterMember::where('cid', $oc['cid'])->first();
                    if ($user && ($user->status != 'Training')) { // Add if on roster, don't if not (big problem lmao)
                        $sessionLog->roster_member_id = $user->id;
                    }
                    // todo: send email to me here if user_id is null
                    // MailController->sendUnauthorisedConnectionEmail(); or something like that

                    // Add session
                    $sessionLog->save();
                }
            }

            // Now check to see if any sessions should be marked as finished
            foreach ($sessionLogs as $log) {
                // Are they still online?
                $stillOnline = false;

                // Loop through online controller list to find a match
                foreach ($onlineControllers as $oc) {
                    if ($oc['cid'] == $log->cid) { // If CID matches
                        $stillOnline = true;
                    }
                }

                // Check if the controller has indeed logged off
                if (!$stillOnline) {
                    // Start and end values parsed so Carbon can understand them
                    $start = Carbon::createFromFormat($log->session_start);
                    $end = Carbon::now();

                    // Calculate difference (difference is the total hours gained)
                    $difference = $start->floatDiffInHours($end);

                    // Populate remaining columns
                    $log->session_end = $end;
                    $log->duration = $difference;

                    // Save the log
                    $log->save();
                }
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
