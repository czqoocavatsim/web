<?php

namespace App\Jobs;

use App\Models\Roster\SoloCertification;
use App\Models\Settings\CoreSettings;
use App\Models\Training\Instructing\Instructors\Instructor;
use App\Notifications\Training\SoloCertifications\SoloCertExpiringStaff;
use App\Notifications\Training\SoloCertifications\SoloCertExpiringUser;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use RestCord\DiscordClient;

class ProcessSoloCertExpiryWarnings implements ShouldQueue
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
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        //Get all active solo certs
        $certs = SoloCertification::where('expires', '>', Carbon::now())->where('expiry_notification_sent', false)->get();

        //Go through certs
        foreach ($certs as $cert) {
            //If cert is about to expire
            if (Carbon::now()->diffInDays($cert->expires) <= 2) {
                //Discord notification in instructors channel
                $discord = new DiscordClient(['token' => config('services.discord.token')]);
                $discord->channel->createMessage([
                    'channel.id' => intval(config('services.discord.instructors')),
                    'content'    => '',
                    'embed'      => [
                        'title'       => 'Solo certification for '.$cert->rosterMember->user->fullName('FLC').' is about to expire.',
                        'url'         => route('training.admin.solocertifications'),
                        'timestamp'   => Carbon::now(),
                        'color'       => hexdec('2196f3'),
                        'description' => 'Expires on '.$cert->expires->toDayDateTimeString().'.',
                    ],
                ]);

                //Notify their instructor
                if ($cert->rosterMember->user->studentProfile && $cert->rosterMember->user->studentProfile->instructor()) {
                    $cert->rosterMember->user->studentProfile->instructor()->instructor->notify(new SoloCertExpiringStaff($cert));
                }

                //Notify CI/ACI
                Notification::route('mail', CoreSettings::find(1)->emailcinstructor)->notify(new SoloCertExpiringStaff($cert));
                if ($aci = Instructor::where('staff_page_tagline', 'Assistant Chief Instructor')->first()) {
                    $aci->notify(new SoloCertExpiringStaff($cert));
                }

                //Notify user
                $cert->rosterMember->user->notify(new SoloCertExpiringUser($cert));

                //Add time to cert
                $cert->expiry_notification_sent = true;
                $cert->expiry_notification_time = Carbon::now();
                $cert->save();
            }
        }
    }
}
