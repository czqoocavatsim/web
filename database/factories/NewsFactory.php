<?php

use Faker\Generator as Faker;

$factory->define(App\News::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'content' => $faker->paragraph,
        'date' => $faker->date('Y-m-d'),
        'type' => 'Email',
        'user_id' => 1300012,
        'archived' => 0,
    ];
});
