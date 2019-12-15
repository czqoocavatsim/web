<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Gander Staff Positions
    |--------------------------------------------------------------------------
    |
    | This file contains definitions for all CZQO staff positions.
    |
    */

    'ZQO1' => [
        'position' => 'Operations Director',
        'type' => 'Director',
        'accessLevel' => 'full',
    ],

    'ZQO2' => [
        'position' => 'Deputy Operations Director',
        'type' => 'Director',
        'accessLevel' => 'full',
    ],

    'ZQO3' => [
        'position' => 'Lead Instructor',
        'type' => 'Director',
        'accessLevel' => 'training',
    ],

    'ZQO4' => [
        'position' => 'Webmaster',
        'type' => 'Director',
        'accessLevel' => 'full',
    ],

    'Instructor' => [
        'position' => 'Instructor',
        'type' => 'AtcTraining',
        'accessLevel' => 'training',
    ],

    'Developer' => [
        'position' => 'Web Developer',
        'type' => 'Maintainer',
        'accessLevel' => 'standard',
    ],
];
