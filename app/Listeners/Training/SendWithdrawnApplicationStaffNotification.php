<?php

namespace App\Listeners\Training;

use App\Events\Training\ApplicationWithdrawn;
use App\Models\Settings\CoreSettings;
use App\Notifications\Training\Applications\ApplicationWithdrawnStaff;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendWithdrawnApplicationStaffNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ApplicationWithdrawn  $event
     * @return void
     */
    public function handle(ApplicationWithdrawn $event)
    {
        //Send notification
        //[Deputy] OCA Chief
        Notification::route('mail', CoreSettings::find(1)->emailfirchief)
        ->route('mail', CoreSettings::find(1)->emaildepfirchief)
        ->notify(new ApplicationWithdrawnStaff($event->application));
    }
}
