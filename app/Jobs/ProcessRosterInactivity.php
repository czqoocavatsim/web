<?php

namespace App\Jobs;

use App\Models\Roster\RosterMember;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Roster\RosterStatusChanged;
use App\Notifications\Roster\RemovedFromRoster;
use App\Services\DiscordClient;

class ProcessRosterInactivity implements ShouldQueue
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
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rosterMembers = RosterMember::whereNotIn('certification', ['not_certified', 'training'])->get();

        foreach ($rosterMembers as $rosterMember) {

            $date = false;
            // Get date certified
            try {
                $certifiedDate = Carbon::createFromFormat('Y-m-d H:i:s', $rosterMember->date_certified);
            } catch (\InvalidArgumentException $e) { // Catch exception if date is null
                $date = true;
            }

            if ($date === false){
                if ($certifiedDate > Carbon::now()->subDays(2)->startOfQuarter() && $certifiedDate < Carbon::now()->subDays(2)->endOfQuarter()){
                    continue;
                }
            }

            if ($rosterMember->active && $rosterMember->currency < 3) {
                $rosterMember->active = false;
                $rosterMember->save();
            }
        }
    }
}
