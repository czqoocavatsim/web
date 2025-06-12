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
        // Active Network Sessions
        $schedule->job(new ProcessSessionLogging())->everyMinute();

        //Discord Update
        $schedule->job(new DiscordAccountCheck())->cron('5,20,35,50 * * * *'); //Updated Hourly

        // External Controllers
        $schedule->job(new ProcessExternalControllers())->cron('7 * * * *'); //Updated Hourly

        //Roster Inactivity checks
        $schedule->job(new ProcessRosterInactivity())->dailyAt('23:55');

        //Training/OTS session reminders
        $schedule->job(new ProcessSessionReminders())->daily();

        // Check Training Threads Status (Saturday)
        $schedule->job(new DiscordTrainingWeeklyUpdates())->weeklyOn(6, '00:01');

        // Mass User (Wednesday)
        $schedule->job((new MassUserUpdates())->onQueue('long'))->weeklyOn(6, '13:10');


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
