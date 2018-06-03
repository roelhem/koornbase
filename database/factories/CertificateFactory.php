<?php

use Faker\Generator as Faker;

$factory->define(\App\Certificate::class, function (Faker $faker) {
    return [
        'person_id' => function() {
            return factory(\App\Person::class)->create();
        },
        'category_id' => function() {
            $result = \App\CertificateCategory::query()->inRandomOrder()->first();
            return $result ?? factory(\App\CertificateCategory::class)->create();
        },
        'examination_at' => function($self) use ($faker) {
            $lower_bound = \Carbon\Carbon::now()->subYears(5);

            $person = \App\Person::find($self['person_id']);
            if($person && $person->birth_date) {
                $lower_bound = $person->getBirthDay(18);
            }

            $upper_bound = \Carbon\Carbon::now();
            $days = $lower_bound->diffInDays($upper_bound);

            return (clone $lower_bound)->addDays($faker->numberBetween(0, $days));
        },
        'passed' => $faker->boolean(80),
        'remarks' => 'Deze Certificate is door een factory aangemaakt. Alleen gebruiken voor test-toepassingen.',
    ];
});
