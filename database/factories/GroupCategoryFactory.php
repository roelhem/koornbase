<?php

use Faker\Generator as Faker;

$factory->define(\App\GroupCategory::class, function (Faker $faker) {
    return [
        'name_short' => $faker->company,
        'name' => function($self) use ($faker) {
            return array_get($self, 'name_short', $faker->company).' (Generated Category)';
        },
        'description' => 'Dit is een GroupCategory die door een factory is gegenereerd. Gebruik deze categorie aleen voor test-toepassingen.',
        'is_required' => false,
    ];
});
