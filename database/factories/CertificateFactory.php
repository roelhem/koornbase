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
        'examination_at' => \Carbon\Carbon::now()->subDays($faker->numberBetween(0, 1000)),
        'passed' => $faker->boolean,
        'remarks' => 'Deze Certificate is door een factory aangemaakt. Alleen gebruiken voor test-toepassingen.',
    ];
});
