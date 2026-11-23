<?php

/*
| Links and videos shown on the Training Hub.
| Set the URLs in .env; anything left empty shows as "coming soon".
| For a video, set 'url' (mp4 or other direct video file) and 'captions' (a .vtt file) once it has been produced.
*/
return [
    'links' => [
        'sop' => env('TRAINING_SOP_URL'),
        'manual' => env('TRAINING_MANUAL_URL'),
        'knowledgebase' => env('TRAINING_KB_URL', 'https://knowledgebase.ganderoceanic.ca'),
    ],

    'videos' => [
        ['title' => 'Welcome: your path to the Oceanic endorsement', 'minutes' => 1.5, 'url' => env('TRAINING_VIDEO_1_URL'), 'captions' => env('TRAINING_VIDEO_1_CAPTIONS')],
        ['title' => 'Application, exam and your live session', 'minutes' => 1.5, 'url' => env('TRAINING_VIDEO_2_URL'), 'captions' => env('TRAINING_VIDEO_2_CAPTIONS')],
        ['title' => 'Oceanic basics: tracks, levels and clearances', 'minutes' => 3, 'url' => env('TRAINING_VIDEO_3_URL'), 'captions' => env('TRAINING_VIDEO_3_CAPTIONS')],
        ['title' => 'What to expect in your live session', 'minutes' => 1.5, 'url' => env('TRAINING_VIDEO_4_URL'), 'captions' => env('TRAINING_VIDEO_4_CAPTIONS')],
        ['title' => 'vNAAATS: the tool you will control with', 'minutes' => 2.5, 'url' => env('TRAINING_VIDEO_5_URL'), 'captions' => env('TRAINING_VIDEO_5_CAPTIONS')],
    ],
];
