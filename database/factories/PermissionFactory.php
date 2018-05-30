<?php

use Faker\Generator as Faker;

$factory->define(\App\Permission::class, function (Faker $faker) {
    return [
        'id' => $faker->lexify('factoryGeneratedPermission:??????????'),
        'name' => 'Permission (Generated by a Factory)',
        'description' => 'Deze Permission is door een factory gegenereerd. Alleen voor test-toepassingen gebruiken!',
    ];
});
