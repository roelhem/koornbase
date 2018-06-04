<?php

use Faker\Generator as Faker;

$factory->define(\App\GroupEmailAddress::class, function (Faker $faker) {
    return [
        'group_id' => function() {
            return factory(\App\Group::class)->create();
        },
        'email_address' => $faker->companyEmail,
        'remarks' => 'Deze GroupEmailAddress is aangemaakt door een factory, alleen voor test-toepassingen gebruiken.'
    ];
});
