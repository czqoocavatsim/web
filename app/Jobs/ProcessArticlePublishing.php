<?php

namespace App\Jobs;

use App\Models\Roster\RosterMember as RosterMember;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Users\User;
use App\Notifications\News as NewsNotification;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use NotificationChannels\Discord\DiscordMessage;
use RestCord\DiscordClient;
use Throwable;

class ProcessArticlePublishing implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $article;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($article)
    {
        $this->article = $article;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Discord client
        $discord = new DiscordClient(['token' => config('services.discord.token')]);

        //Send announcement
        try
        {
            $discord->channel->createMessage([
                'channel.id' => config('app.env') == 'local' ? intval(config('services.discord.web_logs')) : intval(config('services.discord.announcements')),
                'embed' => [
                    'title' => $this->article->title,
                    'description'    => $this->article->summary,
                    'color' => 0x80c9,
                    "image" => [
                        "url" => $this->article->image ? url('/').$this->article->image : null
                    ],
                    "url" => route('news.articlepublic', $this->article->slug),
                    "author" => [
                        "name" => $this->article->author_pretty(),
                    ],
                    "timestamp" => date('Y-m-d H:i:s'),
                ]
            ]);
        }
        catch (Throwable $ex)
        {
            error_log("Webhook failed");
        }

        //Send emails as appropirate
        switch ($this->article->email_level) {
            case 0:
                $discord->channel->createMessage([
                    'channel.id' => intval(config('services.discord.web_logs')),
                    'content' => 'Sent no emails for article '.$this->article->title
                ]);
            break;
            case 1:
                //Send to controllers
                $roster = RosterMember::where('certification', '!=', 'not_certified')->get();
                foreach ($roster as $member) {
                    $member->user->notify(new NewsNotification($member->user, $this->article));
                }
                $discord->channel->createMessage([
                    'channel.id' => intval(config('services.discord.web_logs')),
                    'content' => 'Sent '.count($roster). ' emails to controllers for article '.$this->article->title
                ]);
            break;
            case 2:
                //Send to subscribed users
                $users = User::cursor()->filter(function ($user) {
                    if ($prefs = $user->notificationPreferences) {
                        return $prefs->news_notifications == 'email';
                    }
                    return false;
                });
                foreach ($users as $user) {
                    $user->notify(new NewsNotification($user, $this->article));
                }
                $discord->channel->createMessage([
                    'channel.id' => intval(config('services.discord.web_logs')),
                    'content' => 'Sent '.count($users). ' emails to subscribed users for article '.$this->article->title
                ]);
            break;
            case 3:
                //Send to all
                $users = User::all();
                foreach ($users as $user) {
                    $user->notify(new NewsNotification($user, $this->article));
                }
                $discord->channel->createMessage([
                    'channel.id' => intval(config('services.discord.web_logs')),
                    'content' => 'Sent '.count($users). ' emails to all users for article '.$this->article->title
                ]);
        }
    }
}
