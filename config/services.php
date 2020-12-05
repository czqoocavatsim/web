<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
        'guzzle' => [
            'verify' => true,
            'decode_content' => true,
        ],
        'options' => [
            'open_tracking' => false,
            'click_tracking' => false,
            'transactional' => true,
        ],
    ],
    'discord' => [
        'token' => env('DISCORD_BOT_TOKEN'),
        'client_id' => env('DISCORD_KEY'),
        'client_secret' => env('DISCORD_SECRET'),
        'redirect' => env('DISCORD_REDIRECT_URI'),
        'redirect_server_join_process' => env('DISCORD_REDIRECT_URI').'/server_join_process',
        'redirect_join' => env('DISCORD_REDIRECT_URI_JOIN'),
        'guild_id' => env('DISCORD_GUILD_ID'),
        //Guild IDs
        'web_logs' => env('DISCORD_WEB_LOGS'),
        'guild_logs' => env('DISCORD_GUILD_LOGS'),
        'announcements' => env('DISCORD_ANNOUNCEMENTS'),
        'endorsements' => env('DISCORD_ENDORSEMENTS'),
        'marketing' => env('DISCORD_MARKETING'),
        'staff' => env('DISCORD_STAFF'),
        'instructors' => env('DISCORD_INSTRUCTORS')
    ],
];
