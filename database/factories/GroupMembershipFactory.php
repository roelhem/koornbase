<?php

use Faker\Generator as Faker;

$factory->define(App\GroupMembership::class, function (Faker $faker) {
    return [
        'person_id' => function() {
            return \App\Person::query()->inRandomOrder()->value('id');
        },
        'group_id' => function() {
            return \App\Group::query()->inRandomOrder()->value('id');
        }
    ];
});
