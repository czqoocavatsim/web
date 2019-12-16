<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;
use Illuminate\Support\Carbon;

class ControllerApplication extends Model
{
    protected $table = "event_controller_applications";

    protected $fillable = [
        'id', 'event_id', 'user_id', 'start_availability_timestamp', 'end_availability_timestamp', 'comments', 'submission_timestamp'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function discord_webhook()
    {
        $hook = json_encode([
            /*
             * The general "message" shown above your embeds
             */
            "content" => "A controller has applied to control for " . $this->event->name,
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
                    "title" => $this->user->fullName('FLC'),

                    // The type of your embed, will ALWAYS be "rich"
                    "type" => "rich",

                    // A description for your embed
                    "description" => "",

                    // The URL of where your title will be a link to
                    "url" => route('events.admin.view', $this->event->slug),

                    /* A timestamp to be displayed below the embed, IE for when an an article was posted
                     * This must be formatted as ISO8601
                     */
                    "timestamp" => date('Y-m-d H:i:s'),

                    // The integer color to be used on the left side of the embed
                    "color" => hexdec( "2196f3" ),

                    // Footer object
                    "footer" => [
                        "text" => "Gander Oceanic Core",
                        "icon_url" => asset('img/iconwhitebg.png')
                    ],

                    // Field array of objects
                    "fields" => [
                        // Field 1
                        [
                            "name" => "Timing",
                            "value" => "From " . $this->start_availability_timestamp . " to " . $this->end_availability_timestamp,
                            "inline" => false
                        ],
                        // Field 2
                        [
                            "name" => "Comments",
                            "value" => $this->comments,
                            "inline" => true
                        ],
                    ]
                ]
            ]

        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => config('discord.events.controller_app_webhook'),
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

        if (isset($error)) {
            return false;
        }

        return true;
    }
}
