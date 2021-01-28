<?php

namespace App\Jobs;

use App\Models\Roster\RosterMember;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessInactivityEmail implements ShouldQueue
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

            // Hold hours to report
            $hoursToCheck = null;

            // Get date certified
            $certifiedDate = Carbon::createFromFormat('Y-m-d H:i:s', $rosterMember->date_certified);

            if ($rosterMember->active) {
                // Check if certified in last 6mo
                $diff = Carbon::now()->diffInMonths($certifiedDate); // Get date diff

                // If less than 6 months
                if ($diff <= 6) {
                    switch ($diff) { // Switch the activity and check appropriate hours based on number
                        case 0:
                            break;
                        case 1: // 1 month
                            $hoursToCheck = 1;
                            break;
                        case 2: // 2 months
                            $hoursToCheck = 2;
                            break;
                        case 3: // 3 months
                            $hoursToCheck = 3;
                            break;
                        case 4: // 4 months
                            $hoursToCheck = 4;
                            break;
                        case 5: // 5 months
                            $hoursToCheck = 5;
                            break;
                        default: // Default
                            $hoursToCheck = null;
                            break;
                    }
                } else {
                    // HoursToCheck is 6
                    $hoursToCheck = 6;
                }

                // Now check if they actually have their hours
                $hasHours = $rosterMember->currency < $hoursToCheck ?: false;

                // Action upon if so
                if (!$hasHours) {
                    // todo: send email here. Include something like 'you require {$hoursToCheck} however you only have {$rosterMember->currency}
                    // todo: maybe also find a way to make it so it shows you have 1 month left, 2 weeks left, 1 week left as per the github issue
                }
            }
        }
    }
}
