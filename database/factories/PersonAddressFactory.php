<?php

use Faker\Generator as Faker;

$factory->define(\App\PersonAddress::class, function (Faker $faker) {
    return [
        'person_id' => function() {
            return \App\Person::query()->inRandomOrder()->first()->id;
        },
        'label' => $faker->word,
        'country_code' => $faker->countryCode,
        'administrative_area' => $faker->state,
        'locality' => $faker->city,
        'dependent_locality' => null,
        'postal_code' => $faker->postcode,
        'sorting_code' => $faker->numerify('######'),
        'address_line_1' => $faker->streetAddress,
        'address_line_2' => null,
        'organisation' => null,
        'remarks' => 'Dit adres is automatisch gegenereerd op basis van random gegevens.'
    ];
});