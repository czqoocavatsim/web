<?php

namespace App\Jobs;

use App\Models\AtcTraining\RosterMember;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        // Get all roster members that are certified
        $rosterMembers = RosterMember::all()->whereNotIn('status', ['not_certified', 'training']);

        // Loop through all roster members
        foreach ($rosterMembers as $rosterMember) {

            // Get date certified
            try {
                $certifiedDate = Carbon::createFromFormat('Y-m-d H:i:s', $rosterMember->date_certified);
                error_log($certifiedDate);
            } catch (\InvalidArgumentException $e) { // Catch exception if date is null
                $certifiedDate = null;
            }

            if ($rosterMember->active) {

                // Check if certified in last 6mo
                $diff = $certifiedDate != null ? Carbon::now()->diffInMonths($certifiedDate) : null; // Get date diff

                // If less than 6 months
                if ($diff != null && $diff <= 6) {
                    switch ($diff) { // Switch the activity and check appropriate hours based on number
                        case 0:
                            break;
                        case 1: // 1 month
                            $rosterMember->active = $rosterMember->currency >= 1.0 ?: false;
                            break;
                        case 2: // 2 months
                            $rosterMember->active = $rosterMember->currency >= 2.0 ?: false;
                            break;
                        case 3: // 3 months
                            $rosterMember->active = $rosterMember->currency >= 3.0 ?: false;
                            break;
                        case 4: // 4 months
                            $rosterMember->active = $rosterMember->currency >= 4.0 ?: false;
                            break;
                        case 5: // 5 months
                            $rosterMember->active = $rosterMember->currency >= 5.0 ?: false;
                            break;
                        default: // Default
                            $isActive = true;
                            break;
                    }
                    // Save record
                    $rosterMember->update();
                } else {
                    // Assign to false if less than 6
                    $rosterMember->active = $rosterMember->currency >= 6.0 ?: false;
                    $rosterMember->save();
                }
            } else { // If inactive
                $rosterMember->delete(); // Bye bye
            }
        }
    }
}
