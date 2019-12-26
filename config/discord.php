<?php

return [
    'news_webhook' => env('DISCORD_NEWS_WEBHOOK'),
    'staff_webhook' => env('DISCORD_STAFF_WEBHOOK'),
    'exec_webhook' => env('DISCORD_EXEC_WEBHOOK'),
    'events' => [
        'controller_app_webhook' => env('CONTROLLER_APP_WEBHOOK'),
    ],
    'web_services_webhook' => env('WEB_SERVICES_WEBHOOK'),
];
