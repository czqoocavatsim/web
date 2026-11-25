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

    // Videos and captions live in public/assets/resources/media/training/. Set the .env values only to host them elsewhere.
    'videos' => [
        ['title' => 'Getting started and the airspace', 'minutes' => 11, 'url' => env('TRAINING_VIDEO_1_URL', '/assets/resources/media/training/1-getting-started-and-the-airspace.mp4'), 'captions' => env('TRAINING_VIDEO_1_CAPTIONS', '/assets/resources/media/training/1-getting-started-and-the-airspace.vtt')],
        ['title' => 'Procedures', 'minutes' => 10.5, 'url' => env('TRAINING_VIDEO_2_URL', '/assets/resources/media/training/2-procedures.mp4'), 'captions' => env('TRAINING_VIDEO_2_CAPTIONS', '/assets/resources/media/training/2-procedures.vtt')],
        ['title' => 'Communications and tools', 'minutes' => 11, 'url' => env('TRAINING_VIDEO_3_URL', '/assets/resources/media/training/3-communications-and-tools.mp4'), 'captions' => env('TRAINING_VIDEO_3_CAPTIONS', '/assets/resources/media/training/3-communications-and-tools.vtt')],
        ['title' => 'The entrance exam: what to expect', 'minutes' => 1.5, 'url' => env('TRAINING_VIDEO_4_URL', '/assets/resources/media/training/4-entrance-exam.mp4'), 'captions' => env('TRAINING_VIDEO_4_CAPTIONS', '/assets/resources/media/training/4-entrance-exam.vtt')],
    ],
];
