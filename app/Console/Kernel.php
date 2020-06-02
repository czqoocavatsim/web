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
use App\Mail\ActivityBot\UnauthorisedConnection;
use App\Models\Settings\CoreSettings;
use App\Notifications\Network\ControllerInactive;
use App\Notifications\Network\ControllerIsStudent;
use App\Notifications\Network\ControllerNotCertified;
use App\Notifications\Network\ControllerNotStaff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

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
        // ActivityBot logging
        $schedule->call(function () {

            // Because OOMs
            DB::connection()->disableQueryLog();

            // Load VATSIM data
            $vatsim = new \Vatsimphp\VatsimData();
            $vatsim->loadData();

            // Active lists
            $onlineControllers = array();

            // Getters
            $positions = MonitoredPosition::all();
            $controllers = $vatsim->getControllers();
            $staffOnly = false;

            // Scan controller list for callsign relationships
            foreach ($controllers as $controller) {

                // Flag to set to true if position was in the monitored table
                $identFound = false;

                // Loop through position table
                foreach ($positions as $position) {
                    if (($controller['callsign'] == $position->identifier)) {
                        if ($position->staff_only) {
                            $staffOnly = true;
                        }
                        $identFound = true; // set flag
                        array_push($onlineControllers, $controller); // Add if the callsign is the same as the position identifier
                    }
                }

                // If it wasn't found, check if it has the correct callsign prefix
                if (!$identFound) {
                    if (substr($controller['callsign'], 0, 4) == "CZQX" || substr($controller['callsign'], 0, 4) == "EGGX") {
                        error_log("found");
                        // Add position to table if so, and email
                        $monPos = new MonitoredPosition();
                        $monPos->identifier = $controller["callsign"];
                        $monPos->staff_only = false; // automatic staff only to false
                        $monPos->save();
                        // todo: send email so we know that a new position was created

                        array_push($onlineControllers, $controller); // Add controller because they have logged on an oceanic position
                    }
                }
            }

            // List of session logs
            $sessionLogs =  SessionLog::where("session_end", null)->get();

            // Check logs against currently online controllers
            foreach ($onlineControllers as $oc) {
                error_log("{$oc['cid']}");
                $matchFound = false;
                $ocLogon = null;
                foreach ($sessionLogs as $log) {
                    // Parse logon time lol
                    // Change this to the Y-m-d H:i:s format, as I changed the column type to 'dateTime'
                    $ocLogon = substr($oc['time_logon'], 0, 4).'-'
                        .substr($oc['time_logon'], 4, 2).'-'
                        .substr($oc['time_logon'], 6, 2).' '
                        .substr($oc['time_logon'], 8, 2).':'
                        .substr($oc['time_logon'], 10, 2).':'
                        .substr($oc['time_logon'], 12, 2);

                    // If a match is found
                    if ($ocLogon == $log->session_start) {
                        if (!$log->roster_member_id || RosterMember::where('cid', $log->cid)->first()->status == 'not_certified') { // Check if they're naughty
                            if ($log->emails_sent < 3) {
                                Notification::route('mail', CoreSettings::find(1)->emailfirchief)->notify(new ControllerNotCertified($log));
                                $log->emails_sent++;
                                $log->save();
                            }
                        } else if (!RosterMember::where('cid', $log->cid)->first()->active) { // inactive
                            if ($log->emails_sent < 3) {
                                Notification::route('mail', CoreSettings::find(1)->emailfirchief)->notify(new ControllerInactive($log));
                                $log->emails_sent++;
                                $log->save();
                            }
                        } else if (RosterMember::where('cid', $log->cid)->first()->status == 'training') {
                            if ($log->emails_sent < 3) {
                                Notification::route('mail', CoreSettings::find(1)->emailfirchief)->notify(new ControllerIsStudent($log));
                                error_log('user in training');
                                $log->emails_sent++;
                                $log->save();
                            }
                        } else if ($staffOnly && (RosterMember::where('cid', $log->cid)->first()->status != 'instructor')) { // instructor
                            if ($log->emails_sent < 3) {
                                Notification::route('mail', CoreSettings::find(1)->emailfirchief)->notify(new ControllerNotStaff($log));
                                $log->emails_sent++;
                                $log->save();
                            }
                        }

                        $matchFound = true;
                    } else {
                        continue; // No match was found
                    }
                }

                // Create log variable here so it's within appropriate scope
                $sessionLog = null;

                // If no match was found
                if (!$matchFound) {

                    // Parse logon time again lol
                    // Change this to the Y-m-d H:i:s format, as I changed the column type to 'dateTime'
                    $ocLogon = substr($oc['time_logon'], 0, 4).'-'
                        .substr($oc['time_logon'], 4, 2).'-'
                        .substr($oc['time_logon'], 6, 2).' '
                        .substr($oc['time_logon'], 8, 2).':'
                        .substr($oc['time_logon'], 10, 2).':'
                        .substr($oc['time_logon'], 12, 2);

                    // Build new session log
                    $sessionLog = new SessionLog();
                    $sessionLog->cid = $oc['cid'];
                    $sessionLog->session_start = $ocLogon;
                    $sessionLog->monitored_position_id = MonitoredPosition::where('identifier', $oc['callsign'])->first()->id;
                    $sessionLog->emails_sent = 0;

                    // Check the user's CID against the roster
                    $user = RosterMember::where('cid', $oc['cid'])->first();
                    if ($user && $user->status != 'training' && $user->status != 'not_certified') { // Add if on roster, don't if not (big problem lmao)
                        $sessionLog->roster_member_id = $user->id;
                        if ($staffOnly && ($user->status != 'instructor')) {
                            if ($sessionLog->emails_sent < 3) {
                                Notification::route('mail', CoreSettings::find(1)->emailfirchief)->notify(new ControllerNotStaff($sessionLog));
                                $sessionLog->emails_sent++;
                                $sessionLog->save();
                            }
                        } else if (!$user->active) { // inactive
                            if ($sessionLog->emails_sent < 3) {
                                Notification::route('mail', CoreSettings::find(1)->emailfirchief)->notify(new ControllerInactive($sessionLog));
                                $sessionLog->emails_sent++;
                                $sessionLog->save();
                            }
                        }
                    } else { // Send unauthorised notification to FIR Chief
                        if ($user) $sessionLog->roster_member_id = $user->id;
                        if (!$user->active) { // inactive
                            if ($sessionLog->emails_sent < 3) {
                                Notification::route('mail', CoreSettings::find(1)->emailfirchief)->notify(new ControllerInactive($sessionLog));
                                $sessionLog->emails_sent++;
                                $sessionLog->save();
                            }
                        } else if ($user->status == 'training') {
                            if ($sessionLog->emails_sent < 3) {
                                Notification::route('mail', CoreSettings::find(1)->emailfirchief)->notify(new ControllerIsStudent($sessionLog));
                                $sessionLog->emails_sent++;
                                $sessionLog->save();
                            }
                        } else if ($sessionLog->emails_sent < 3) {
                            Notification::route('mail', CoreSettings::find(1)->emailfirchief)->notify(new ControllerNotCertified($sessionLog));
                            $sessionLog->emails_sent++;
                            $sessionLog->save();
                        }
                    }

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
                    $start = Carbon::create($log->session_start);
                    $end = Carbon::now();

                    // Calculate decimal difference (difference is the total hours gained) ie. 30 minutes = 0.5
                    $difference = $start->floatDiffInMinutes($end) / 60;

                    // Populate remaining columns
                    $log->session_end = $end;
                    $log->duration = $difference;

                    error_log($difference);

                    // Save the log
                    $log->save();

                    // Add hours
                    $roster_member = RosterMember::where('cid', $log->cid)->first();

                    // check it exists
                    if ($roster_member) {
                        if ($roster_member->status == 'certified' || $roster_member->status == 'instructor') {
                            if ($roster_member->active) {
                                // Add hours
                                $roster_member->currency = $roster_member->currency + $difference;

                                // Add hours to leaderboard
                                $roster_member->monthly_hours = $roster_member->monthly_hours + $difference;

                                // Save roster member
                                $roster_member->save();
                            }
                        }
                    }
                }
            }
        })->everyMinute();

        // 6 monthly currency wipe
        $schedule->call(function () {
            // Loop through all roster members
            foreach (RosterMember::all() as $rosterMember) {
                // Reset the hours for every member
                $rosterMember->currency = 0.0;
                $rosterMember->save();
            }
        })->cron("0 0 1 */6 *");

        //// CRONS FOR INACTIVITY EMAILS
        /// June
        $schedule->call(function () {

        })->cron("0 0 1 6 *"); // 1 month before end of period

        $schedule->call(function () {

        })->cron("0 0 17 6 *"); // 2 weeks before end of period

        $schedule->call(function () {

        })->cron("0 0 24 6 *"); // 1 week before end of period

        /// December
        $schedule->call(function () {

        })->cron("0 0 1 12 *"); // 1 month before end of period

        $schedule->call(function () {

        })->cron("0 0 16 12 *"); // 2 weeks before end of period

        $schedule->call(function () {

        })->cron("0 0 23 12 *"); // 1 week before end of period

        // Monthly leaderboard wipe
        $schedule->call(function () {
            // Loop through all roster members
            foreach (RosterMember::all() as $rosterMember) {
                // Reset the hours for every member
                $rosterMember->monthly_hours = 0.0;
                $rosterMember->save();
            }
        })->monthlyOn(1, '00:00');

        // Daily roster rating check
        $schedule->call(function () {
            // Loop through all roster members
            foreach (RosterMember::all() as $rosterMember) {
                // Get corresponding user
                $user = \App\Models\Users\User::all()->where('id', '==', $rosterMember->cid);

                // Check if the rating is incorrect
                if ($user->rating_short != $rosterMember->rating) {
                    // If so, then reassign the rating
                    $rosterMember->rating = $user->rating_short;
                }
            }
        })->dailyAt('00:00');
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
