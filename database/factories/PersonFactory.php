<?php

use Faker\Generator as Faker;

$factory->define(App\Person::class, function (Faker $faker) {

    $firstName = $faker->firstName();
    $lastName = $faker->lastName();

    return [
        'name' => $firstName.' '.$lastName,
        'name_short' => $firstName,
        'name_formal' => $faker->title.' '.$firstName.' '.$lastName,
        'nickname' => substr($faker->userName, 0, 16),
        'birth_date' => $faker->dateTimeBetween('-30 years', '-15 years'),
        'remarks' => 'Deze Person is met een factory gegenereerd op basis van willekeurige data. (Alleen voor test-toepassingen)'
    ];
});
