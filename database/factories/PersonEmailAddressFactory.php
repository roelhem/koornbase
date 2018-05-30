<?php

use Faker\Generator as Faker;

$factory->define(App\PersonEmailAddress::class, function (Faker $faker) {
    return [
        'person_id' => function() {
            return \App\Person::query()->inRandomOrder()->value('id');
        },
        'label' => $faker->word,
        'email_address' => $faker->safeEmail,
        'remarks' => 'Gegenereerd door een Factory.'
    ];
});