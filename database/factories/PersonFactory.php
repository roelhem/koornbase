<?php

use Faker\Generator as Faker;

$factory->define(App\Person::class, function (Faker $faker) {

    $gender = $faker->boolean;

    // Generate the first name
    $firstName = $faker->firstName($gender);

    // Generating the middle name and initials.
    $initials = substr($firstName, 0, 1).'.';
    $middleNames = [];
    while($faker->boolean(40)) {
        $name = $faker->firstName($gender);
        $initials .= substr($name, 0, 1).'.';
        $middleNames[] = $name;
    }

    // Generate the prefix and last name
    $name = $faker->lastName();
    preg_match( '/[A-Z]/', $name, $matches, PREG_OFFSET_CAPTURE );
    if(count($matches) > 0) {
        $offset = $matches[0][1];
        $prefix = trim(substr($name, 0, $offset));
        $lastName = trim(substr($name, $offset));
    } else {
        $prefix = null;
        $lastName = trim($name);
    }

    return [
        'name_initials' => trim($initials),
        'name_first' => trim($firstName),
        'name_middle' => count($middleNames) > 0 ? implode(' ', $middleNames) : null,
        'name_prefix' => empty($prefix) ? null : $prefix,
        'name_last' => $lastName,
        'name_nickname' => $faker->userName,
        'birth_date' => $faker->dateTimeBetween('-30 years', '-15 years'),
    ];
});
