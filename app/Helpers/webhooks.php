<?php
/**
 * Created by PhpStorm.
 * User: Liesel
 * Date: 2/03/2019
 * Time: 2:12 AM.
 */
function createNewsMessage(App\News $article)
{
    $hookObject = json_encode([
        /*
         * The general "message" shown above your embeds
         */
        'content' => 'A new article has been released on the CZQO website!',
        /*
         * The username shown in the message
         */
        'username' => 'Gander Oceanic Core',
        /*
         * The image location for the senders image
         */
        'avatar_url' => 'https://cdn.discordapp.com/avatars/482857020496805898/e1986c594dde3c0feec30e084a41a3e3.png?size=256',
        /*
         * Whether or not to read the message in Text-to-speech
         */
        'tts' => false,
        /*
         * File contents to send to upload a file
         */
        // "file" => "",
        /*
         * An array of Embeds
         */
        'embeds' => [
            [
                // Set the title for your embed
                'title' => $article->title,

                // The type of your embed, will ALWAYS be "rich"
                'type' => 'rich',

                // A description for your embed
                'description' => '',

                // The URL of where your title will be a link to
                'url' => url('news/'.$article->slug),

                'timestamp' => date('Y-m-d H:i:s'),

                // The integer color to be used on the left side of the embed
                'color' => hexdec('FFFFFF'),

                'footer' => [
                    'text' => 'Created '.date('Y-m-d H:i:s'),
                ],

                'author' => [
                    'name' => \App\User::find($article->user_id)->fname.' '.\App\User::find($article->user_id)->lname.' '.\App\User::find($article->user_id)->id,
                ],

                /*"fields" => [
                    [
                        "name" => "Data A",
                        "value" => "Value A",
                        "inline" => false
                    ],
                ]*/
            ],
        ],

    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => config('discord.news_webhook'),
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $hookObject,
        CURLOPT_HTTPHEADER => [
            'Length' => strlen($hookObject),
            'Content-Type' => 'application/json',
        ],
    ]);

    $response = curl_exec($ch);
    curl_close($ch);
}
