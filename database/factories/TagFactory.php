<?php

use Faker\Generator as Faker;

$factory->define(App\Tag::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'name_short' => $faker->word,
        'description' => $faker->text,
        'visibility' => $faker->numberBetween(1, 100),
        'remarks' => $faker->realText(),
    ];
});
