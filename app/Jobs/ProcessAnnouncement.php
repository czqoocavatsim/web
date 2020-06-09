<?php

namespace App\Jobs;

use App\Models\Users\User;
use App\Notifications\News\Announcement as AnnouncementNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RestCord\DiscordClient;

class ProcessAnnouncement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $announcement;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($announcement)
    {
        $this->announcement = $announcement;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Discord
        $discord = new DiscordClient(['token' => config('services.discord.token')]);

        //Who is it directed to
        switch ($this->announcement->target_group)
        {
            case "everyone":
                //Every user
                $users = User::all();
                foreach ($users as $user) {
                    $user->notify(new AnnouncementNotification($user, $this->announcement));
                }
                $discord->channel->createMessage(['channel.id' => 482860026831175690, 'content' => 'Sent '. count($users) . ' emails for announcement '.$this->announcement->title]);
                break;
            case "roster":
                // All roster members
                $rosterMembers = RosterMember::all();
                foreach ($rosterMember as $member) {
                    $member->notify(new AnnouncementNotification($member, $this->announcement));
                }
                $discord->channel->createMessage(['channel.id' => 482860026831175690, 'content' => 'Sent '. count($users) . ' emails for announcement '.$this->announcement->title]);
                break;
        }
    }
}
