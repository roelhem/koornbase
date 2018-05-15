<?php

use Faker\Generator as Faker;

$factory->define(\App\Budget::class, function (Faker $faker) {

    $comm = \App\Group::where('category_id','LIKE','comm%')->inRandomOrder()->first()->name;
    $year = $faker->year;

    $start = $faker->dateTimeThisCentury();
    $end = $faker->dateTimeBetween($start);

    return [
        'name' => $comm.' budget '.$year,
        'name_short' => $comm.' '.$year,
        'description' => $faker->text(),
        'remarks' => $faker->realText(),
        'start' => $start,
        'end' => $end,
        'amount' => $faker->randomFloat(2, 0, 10000),
    ];
});
