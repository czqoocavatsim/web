<?php

namespace App\Console;

use App\Jobs\ProcessRosterInactivity;
use App\Jobs\ProcessSessionLogging;
use App\Jobs\ProcessSessionReminders;
use App\Jobs\ProcessSoloCertExpiryWarnings;
use App\Jobs\ProcessExternalControllers;
use App\Jobs\DiscordTrainingWeeklyUpdates;
use App\Jobs\ProcessMonthlyBreakdown;
use App\Jobs\UpdateDiscordUserRoles;
use App\Jobs\DiscordAccountCheck;
use App\Jobs\MassUserUpdates;
use App\Jobs\ProcessAirlines;
use App\Jobs\ProcessAirports;
use App\Jobs\ProcessAircraft;
use App\Jobs\ProcessStatistics;

use App\Models\Roster\RosterMember;
use App\Notifications\Network\OneWeekInactivityReminder;
use App\Notifications\Network\TwoWeekInactivityReminder;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Services\DiscordClient;


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
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        # PRIMARY WORKER
        // Minute by Minute Updates
        $schedule->job(new ProcessSessionLogging())->everyMinute();

        // Hourly Updates
        $schedule->job(new DiscordAccountCheck())->cron('5,20,35,50 * * * *');
        $schedule->job(new ProcessExternalControllers())->cron('7 * * * *');
        $schedule->job(new ProcessStatistics())->cron('01 * * * *');

        //Daily Updates
        $schedule->job(new ProcessRosterInactivity())->dailyAt('23:55');
        $schedule->job(new ProcessSessionReminders())->daily();
        
        // Weekly Updates
        $schedule->job((new DiscordTrainingWeeklyUpdates())->onQueue('long'))->weeklyOn(6, '00:01');
        $schedule->job((new MassUserUpdates())->onQueue('long'))->weeklyOn(6, '13:10');
        $schedule->job((new ProcessAirlines())->onQueue('long'))->weeklyOn(4, '12:00');
        $schedule->job((new ProcessAirports())->onQueue('long'))->weeklyOn(4, '12:10');
        $schedule->job((new ProcessAircraft())->onQueue('long'))->weeklyOn(4, '12:20');


        // Monthly Statistics Breakdown
        $schedule->job(new ProcessMonthlyBreakdown())->monthlyOn(1, '00:01');
       
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
