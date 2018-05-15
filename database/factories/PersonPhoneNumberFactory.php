<?php

use Faker\Generator as Faker;

$factory->define(App\PersonPhoneNumber::class, function (Faker $faker) {

    return [
        'person_id' => function() {
            return \App\Person::query()->inRandomOrder()->value('id');
        },
        'label' => $faker->word,
        'is_primary' => $faker->boolean,
        'for_emergency' => $faker->boolean,
        'phone_number' => '+31612345678',
        'is_mobile' => true,
        'remarks' => 'Gegenereerd door een Factory.'
    ];
});
