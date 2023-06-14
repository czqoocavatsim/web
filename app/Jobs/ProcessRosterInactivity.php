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
use Illuminate\Support\Facades\Log;

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
        $rosterMembers = RosterMember::all()->whereNotIn('certification', ['not_certified', 'training']);

        foreach ($rosterMembers as $rosterMember) {

            // Get date certified
            try {
                $certifiedDate = Carbon::createFromFormat('Y-m-d H:i:s', $rosterMember->date_certified);
            } catch (\InvalidArgumentException $e) { // Catch exception if date is null
                Log::error('Certified Date Error '.$rosterMember->user_id);
                continue;
            }

            if ($rosterMember->active && !($certifiedDate > Carbon::now()->startOfQuarter() && $certifiedDate < Carbon::now()->endOfQuarter())) {
                
                if ($rosterMember->currency < 6.0) {
                    $rosterMember->active = false;
                    $rosterMember->save();
                    Notification::send($rosterMember->user, new RosterStatusChanged($rosterMember));
                }    
            }elseif (!$rosterMember->active) {
                $rosterMember->delete();
                Notification::send($rosterMember->user, new RemovedFromRoster($rosterMember));
            }
        }
    }
}
