<?php

/*
| Links and videos shown on the Training Hub.
| Set the URLs in .env; anything left empty shows as "coming soon".
| For a video, set 'url' (mp4 or other direct video file) and 'captions' (a .vtt file) once it has been produced.
*/
return [
    // Discord server where students talk with their instructor and each other.
    'discord_invite' => env('TRAINING_DISCORD_INVITE', 'https://discord.gg/nQKAFpf98J'),

    'links' => [
        'sop' => env('TRAINING_SOP_URL', 'https://ganderoceanic.ca/assets/resources/files/atc/ShanwickEGGXGanderCZQOOceanicControlProcedures.pdf'),
        'training_policy' => env('TRAINING_POLICY_URL', 'https://ganderoceanic.ca/assets/resources/files/policies/CZQOP2_Training_v3.1.pdf'),
        'phraseology' => env('TRAINING_PHRASEOLOGY_URL', 'https://ganderoceanic.ca/assets/resources/files/atc/Gander_Oceanic_Phraseology_Sheet_1.1.pdf'),
        'currency_policy' => env('TRAINING_CURRENCY_URL', 'https://ganderoceanic.ca/assets/resources/files/policies/CZQOP4_Currency_v1.5.pdf'),
        'knowledgebase' => env('TRAINING_KB_URL', 'https://knowledgebase.ganderoceanic.ca'),
    ],

    // Set each video's url (mp4) and captions (.vtt) in .env once the files are hosted.
    'videos' => [
        ['title' => 'Getting started and the airspace', 'minutes' => 8, 'url' => env('TRAINING_VIDEO_1_URL'), 'captions' => env('TRAINING_VIDEO_1_CAPTIONS')],
        ['title' => 'Procedures', 'minutes' => 8, 'url' => env('TRAINING_VIDEO_2_URL'), 'captions' => env('TRAINING_VIDEO_2_CAPTIONS')],
        ['title' => 'Communications and tools', 'minutes' => 7.5, 'url' => env('TRAINING_VIDEO_3_URL'), 'captions' => env('TRAINING_VIDEO_3_CAPTIONS')],
        ['title' => 'The entrance exam: what to expect', 'minutes' => 1.5, 'url' => env('TRAINING_VIDEO_4_URL'), 'captions' => env('TRAINING_VIDEO_4_CAPTIONS')],
    ],
];
