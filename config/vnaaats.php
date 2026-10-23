<?php

/*
| vNAAATS V2 (https://vnaaats.com): personal plugin keys and Flight Data Display passwords.
| Put these in .env:
|   VNAAATS_URL=https://vnaaats.com
|   VNAAATS_ISSUER_SECRET=<the VNAAATS_ISSUER_SECRET value from /etc/vnaaats.env on the vNAAATS server>
*/
return [
    'url' => rtrim(env('VNAAATS_URL', 'https://vnaaats.com'), '/'),
    'issuer_secret' => env('VNAAATS_ISSUER_SECRET'),
];
