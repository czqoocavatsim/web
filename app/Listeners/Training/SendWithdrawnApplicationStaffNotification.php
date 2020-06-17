<?php

namespace App\Listeners\Training;

use App\Events\Training\ApplicationWithdrawn;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
        //
    }
}
