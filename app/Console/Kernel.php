<?php

namespace App\Console;

use App\VatsimSession;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\User;
use App\AuditLogEntry;
use App\VatsimPosition;

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
            AuditLogEntry::insert(User::find(1), "Application  accepted and controller added to roster, status training", User::find(1), 0);
            error_log('Some message here.');
            //Record controller sessions
            $logFile = __DIR__.'/vendor/skymeyer/vatsimphp/app/logs/pilots.log';
            $vatsim = new \Vatsimphp\VatsimData();
            $vatsim->setConfig('cacheOnly', false);
            $vatsim->setConfig('logFile', $logFile);
            if (!$vatsim->loadData()) {
                log('No VATSIM data ('.Carbon::now().')');
            } else {
                $positions = VatsimPosition::all();
                foreach ($positions as $position) {
                    $controllers = $vatsim->searchCallsign($position->callsign);
                    foreach ($controllers as $controller) {
                        $sessionFound = false;
                        $sessions = VatsimSession::where('status', 0)->get();
                        foreach ($sessions as $s) {
                            if ($s->position === $position) $sessionFound = true;
                        }
                        $session = new VatsimSession([
                            'controller' => 1,
                            'vatsim_cid' => $controller['cid'],
                            'position' => $position->id,
                            'session_start' => date('Y-m-d H:i:s'),
                            'session_end' => date('Y-m-d H:i:s')
                        ]);
                        $session->save();
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
