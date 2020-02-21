<?php

namespace App\Jobs;

use App\Models\AtcTraining\RosterMember;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Users\User;
use App\Notifications\News as NewsNotification;
use Illuminate\Support\Facades\Log;
use RestCord\DiscordClient;

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
        //Publish on Discord webhook
        $hook = json_encode([
            /*
             * The general "message" shown above your embeds
             */
            "content" => null,
            /*
             * The username shown in the message
             */
            "username" => "Gander Oceanic Core",
            /*
             * The image location for the senders image
             */
            "avatar_url" => asset('img/iconwhitebg.png'),
            /*
             * Whether or not to read the message in Text-to-speech
             */
            "tts" => false,
            /*
             * File contents to send to upload a file
             */
            // "file" => "",
            /*
             * An array of Embeds
             */
            "embeds" => [
                /*
                 * Our first embed
                 */
                [
                    // Set the title for your embed
                    "title" => $this->article->title,

                    // The type of your embed, will ALWAYS be "rich"
                    "type" => "rich",

                    // A description for your embed
                    "description" => $this->article->summary,

                    // The URL of where your title will be a link to
                    "url" => route('news.articlepublic', $this->article->slug),

                    /* A timestamp to be displayed below the embed, IE for when an an article was posted
                     * This must be formatted as ISO8601
                     */
                    "timestamp" => date('Y-m-d H:i:s'),

                    // The integer color to be used on the left side of the embed
                    "color" => hexdec( "2196f3" ),

                    "image" => [
                        "url" => $this->article->image ? "https://czqo.vatcan.ca/".$this->article->image : null
                    ],

                    // Footer object
                    "footer" => [
                        "text" => "Gander Oceanic Core",
                        "icon_url" => asset('img/iconwhitebg.png')
                    ],

                ]
            ]

        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
        Log::info($hook);
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => config('discord.news_webhook'),
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $hook,
            CURLOPT_HTTPHEADER => [
                'Length' => strlen($hook),
                "Content-Type: application/json"
            ]
        ]);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $error = curl_error($ch);
        }
        curl_close($ch);
        Log::info(config('discord.news_webhook'));

        //Send emails as appropirate
        switch ($this->article->email_level) {
            case 0:
                $discord = new DiscordClient(['token' => config('services.discord.token')]);
                $discord->channel->createMessage(['channel.id' => 482860026831175690, 'content' => 'Sent no emails for article '.$this->article->title]);
            break;
            case 1:
                //Send to controllers
                $roster = RosterMember::where('status', '!=', 'not_certified')->get();
                foreach ($roster as $member) {
                    $member->user->notify(new NewsNotification($member->user, $this->article));
                }
                $discord = new DiscordClient(['token' => config('services.discord.token')]);
                $discord->channel->createMessage(['channel.id' => 482860026831175690, 'content' => 'Sent '.count($roster). ' emails to controllers for article '.$this->article->title]);
            break;
            case 2:
                //Send to subscribed users
                $users = User::where('gdpr_subscribed_emails', 1)->get();
                foreach ($users as $user) {
                    $user->notify(new NewsNotification($user, $this->article));
                }
                $discord = new DiscordClient(['token' => config('services.discord.token')]);
                $discord->channel->createMessage(['channel.id' => 482860026831175690, 'content' => 'Sent '.count($users). ' emails to subscribed users for article '.$this->article->title]);
            break;
            case 3:
                //Send to all
                $users = User::all();
                foreach ($users as $user) {
                    $user->notify(new NewsNotification($user, $this->article));
                }
                $discord = new DiscordClient(['token' => config('services.discord.token')]);
                $discord->channel->createMessage(['channel.id' => 482860026831175690, 'content' => 'Sent '.count($users). ' emails to all users for article '.$this->article->title]);
        }
    }
}
