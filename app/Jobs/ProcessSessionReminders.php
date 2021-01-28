<?php

namespace App\Jobs;

use App\Models\Training\Instructing\Records\OTSSession;
use App\Models\Training\Instructing\Records\TrainingSession;
use App\Notifications\Training\Instructing\Session24HrReminder;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessSessionReminders implements ShouldQueue
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
        //Training sessions

        //Get all future sessions where reminder is not yet sent
        $futureTrainingSessions = TrainingSession::where('scheduled_time', '>', Carbon::now())->where('reminder_sent', false)->get();

        //Go through sessions
        foreach ($futureTrainingSessions as $session) {
            //If session is in 24 hours or less
            if (Carbon::now()->diffInHours($session->scheduled_time) <= 24) {
                //Send reminder to student
                $session->student->user->notify(new Session24HrReminder($session, 'training'));

                //Mark session
                $session->reminder_sent = true;
                $session->save();
            }
        }

        //OTS sessions

        //Get all future sessions where reminder is not yet sent
        $futureOtsSessions = OTSSession::where('scheduled_time', '>', Carbon::now())->where('reminder_sent', false)->get();

        //Go through sessions
        foreach ($futureOtsSessions as $session) {
            //If session is in 24 hours or less
            if (Carbon::now()->diffInHours($session->scheduled_time) <= 24) {
                //Send reminder to student
                $session->student->user->notify(new Session24HrReminder($session, 'ots'));

                //Mark session
                $session->reminder_sent = true;
                $session->save();
            }
        }
    }
}
