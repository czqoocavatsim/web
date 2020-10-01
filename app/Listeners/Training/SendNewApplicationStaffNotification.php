<?php

namespace App\Listeners\Training;

use App\Events\Training\ApplicationSubmitted;
use App\Models\Settings\CoreSettings;
use App\Notifications\Training\Applications\NewApplicationStaff;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendNewApplicationStaffNotification
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
     * @param  ApplicationSubmitted  $event
     * @return void
     */
    public function handle(ApplicationSubmitted $event)
    {
        //Send notification
        //[Deputy] OCA Chief
        Notification::route('mail', CoreSettings::find(1)->emailfirchief)
                    ->route('mail', CoreSettings::find(1)->emaildepfirchief)
                    ->notify(new NewApplicationStaff($event->application));
    }
}
