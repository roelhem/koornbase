<?php

use Faker\Generator as Faker;

$factory->define(App\Event::class, function (Faker $faker) {
    return [
        'category_id' => function() {
            return \App\EventCategory::query()->inRandomOrder()->first()->id;
        },
        'venue_id' => function() {
            $venue = \App\Venue::query()->inRandomOrder()->first();
            if($venue) {
                return $venue->id;
            } else {
                return null;
            }
        },
        'manager_id' => function() {
            return \App\Person::query()->inRandomOrder()->first()->id;
        },
        'debtor_id' => function() {
            $debtor = \App\Debtor::query()->inRandomOrder()->first();
            if($debtor) {
                return $debtor->id;
            } else {
                return null;
            }
        },
        'name' => $faker->company,
        'description' => $faker->realText(),
        'start' => function($self) use ($faker) {
            if($self['end'] instanceof DateTime) {
                return $faker->dateTimeBetween('-2 years', $self['end']->format('c'));
            } else {
                return $faker->dateTimeBetween('-2 years', '+1 years');
            }
        },
        'end' => function($self) use ($faker) {
            if($self['start'] instanceof DateTime) {
                return $faker->dateTimeBetween($self['start']->format('c'), '+2 years');
            } else {
                return $faker->dateTimeBetween('-1 years','+2 years');
            }
        },
        'is_open' => $faker->boolean,
        'visibility' => 20,
        'remarks' => 'Dit event is gegenereerd door een Factory.'
    ];
});
