<?php

use Faker\Generator as Faker;

$factory->define(App\CertificateCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->lexify('###### Certificaat'),
        'name_short' => $faker->lexify('####'),
        'description' => 'Een CertificateCategory die door een factory is aangemaakt. Alleen gebruiken voor test-toepassingen.',
        'default_expire_years' => $faker->numberBetween(1,10),
        'is_required' => false
    ];
});

$factory->state(App\CertificateCategory::class, 'does_not_expire' , ['default_expire_years' => null]);
