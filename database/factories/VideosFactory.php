<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(App\Entities\Video::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'date' => Carbon::now(), 
    ];
});
