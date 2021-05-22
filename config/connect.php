<?php

return [
    'client_id' => env('CONNECT_CLIENT_ID'),
    'secret'    => env('CONNECT_SECRET'),
    'redirect'  => env('CONNECT_REDIRECT_URI'),
    'endpoint'  => env('APP_ENV') == 'local' ? 'https://auth-dev.vatsim.net' : 'https://auth.vatsim.net'
];
