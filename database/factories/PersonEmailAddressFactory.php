<?php

use Faker\Generator as Faker;

$factory->define(App\PersonEmailAddress::class, function (Faker $faker) {
    return [
        'person_id' => function() {
            $person = \App\Person::query()->inRandomOrder()->first();
            if($person instanceof \App\Person) {
                return $person->id;
            } else {
                return factory(\App\Person::class)->create()->id;
            }
        },
        'label' => $faker->word,
        'email_address' => $faker->safeEmail,
        'remarks' => 'Gegenereerd door een Factory.'
    ];
});