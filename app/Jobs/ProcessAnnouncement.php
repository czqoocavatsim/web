<?php

namespace App\Jobs;

use App\Models\Roster\RosterMember;
use App\Models\Training\Instructing\Instructor;
use App\Models\Training\Instructing\Student;
use App\Models\Users\StaffMember;
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
                $discord->channel->createMessage(['channel.id' => intval(config('services.discord.web_logs')), 'content' => 'Sent '. count($users) . ' emails for announcement '.$this->announcement->title]);
                break;
            case "roster":
                // All active roster members
                $rosterMembers = RosterMember::where('active', 1)->get();
                foreach ($rosterMembers as $member) {
                    $member->user->notify(new AnnouncementNotification($member->user, $this->announcement));
                }
                $discord->channel->createMessage(['channel.id' => intval(config('services.discord.web_logs')), 'content' => 'Sent '. count($rosterMembers) . ' emails to roster members for announcement '.$this->announcement->title]);
                break;
            case "staff":
                // All active staff members
                $staffMembers = StaffMember::where('user_id', '!=', 1)->get();
                foreach ($staffMembers as $member) {
                    $member->user->notify(new AnnouncementNotification($member->user, $this->announcement));
                }
                $discord->channel->createMessage(['channel.id' => intval(config('services.discord.web_logs')), 'content' => 'Sent '. count($staffMembers) . ' emails to staff members for announcement '.$this->announcement->title]);
                break;
            case "students":
                // All active students
                $students = Student::whereCurrent(true)->get();
                foreach ($students as $member) {
                    $member->user->notify(new AnnouncementNotification($member->user, $this->announcement));
                }
                $discord->channel->createMessage(['channel.id' => intval(config('services.discord.web_logs')), 'content' => 'Sent '. count($students) . ' emails to current students for announcement '.$this->announcement->title]);
            break;
            case "students":
                // All active students
                $students = Instructor::whereCurrent(true)->get();
                foreach ($students as $member) {
                    $member->user->notify(new AnnouncementNotification($member->user, $this->announcement));
                }
                $discord->channel->createMessage(['channel.id' => intval(config('services.discord.web_logs')), 'content' => 'Sent '. count($students) . ' emails to current instructors for announcement '.$this->announcement->title]);
                break;
        }
    }
}
