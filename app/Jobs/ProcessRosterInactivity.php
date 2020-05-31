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
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
            } catch (\InvalidArgumentException $e) {
                $certifiedDate = null;
            }

            if($rosterMember->active) {

                // Check if certified in last 6mo
                $diff = $certifiedDate != null ? Carbon::now()->diffInMonths($certifiedDate) : null; // Get date diff

                // If less than 6 months
                if ($diff != null && $diff <= 6) {
                    switch($diff) { // Switch the activity and check appropriate hours based on number
                        case 0:
                            break;
                        case 1: // 1 month
                            $rosterMember->currency < 1.0 ?: $rosterMember->active = false;
                            break;
                        case 2: // 2 months
                            $rosterMember->currency < 2.0 ?: $rosterMember->active = false;
                            break;
                        case 3: // 3 months
                            $rosterMember->currency < 3.0 ?: $rosterMember->active = false;
                            break;
                        case 4: // 4 months
                            $rosterMember->currency < 4.0 ?: $rosterMember->active = false;
                            break;
                        case 5: // 5 months
                            $rosterMember->currency < 5.0 ?: $rosterMember->active = false;
                            break;
                        default: // Default
                            $rosterMember->active = true;
                            break;
                    }
                    // Save record
                    $rosterMember->save();
                }
                else {
                    // Assign to false if less than 6
                    $rosterMember->currency < 6.0 ?: $rosterMember->active = false;
                    error_log("less than 6 $rosterMember->cid");
                    $saved = $rosterMember->save();
                }
            }
            else { // If inactive
                $rosterMember->delete(); // Bye bye
            }
        }
    }
}
