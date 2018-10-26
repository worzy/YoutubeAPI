<?php

use Faker\Generator as Faker;

$factory->define(App\Entities\Channel::class, function (Faker $faker) {
    return [
        'channel_name' => $faker->name,
    ];
});
