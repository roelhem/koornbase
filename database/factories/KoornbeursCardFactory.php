<?php

use Faker\Generator as Faker;
use \App\Person;
use \Carbon\Carbon;

$factory->define(App\KoornbeursCard::class, function (Faker $faker) {
    return [
        'owner_id' => function() {
            $person = Person::query()->inRandomOrder()->first();
            if($person) {
                return $person->id;
            } else {
                return null;
            }
        },
        'ref' => $faker->numerify('T##########'),
        'version' => 'unexistantTestVersion',
        'activated_at' => function($self) use ($faker) {
            $upperBound = array_get($self, 'deactivated_at');
            if($upperBound instanceof DateTime) {
                $upperBound = $upperBound->format('c');
            }
            if(!is_string($upperBound)) {
                $upperBound = 'now';
            }

            return $faker->dateTimeBetween('-5 years', $upperBound);
        },
        'deactivated_at' => function($self) use ($faker) {
            $lowerBound = array_get($self, 'activated_at');
            if($lowerBound instanceof DateTime) {
                $lowerBound = $lowerBound->format('c');
            }
            if(!is_string($lowerBound)) {
                $lowerBound = '-5 years';
            }
            return $faker->dateTimeBetween($lowerBound, 'now');
        },
        'remarks' => 'Deze KoornbeursCard is gegenereerd met behulp van een factory. Alleen gebruiken voor test-toepassingen.'
    ];
});

$factory->state(App\KoornbeursCard::class, 'active', [
    'deactivated_at' => null,
]);

$factory->state(App\KoornbeursCard::class, 'no-owner', [
    'owner_id' => null
]);