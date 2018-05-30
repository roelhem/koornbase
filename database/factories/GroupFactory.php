<?php

use Faker\Generator as Faker;

$factory->define(\App\Group::class, function (Faker $faker) {
    return [
        'member_name' => $faker->jobTitle,
        'name_short' => function($self) {
            return str_plural(array_get($self, 'member_name'));
        },
        'name' => function($self) use ($faker) {
            return array_get($self, 'name_short').' (Generated Group)';
        },
        'description' => 'Deze groep is gemaakt met een factory. Gebuik deze group alleen voor test-toepassingen.',
        'is_required' => false,
        'category_id' => function() {
            $factoryGroupCategory = \App\GroupCategory::firstOrCreate([
                'name' => 'Groepen gegenereerd door factory(\App\Group::class).',
                'name_short' => 'Factory Groepen',
                'description' => 'Deze groepen zijn door factory(\App\Group::class) gegenereerd zonder dat er een category_id was opgegeven. Gebruik deze groepen alleen voor test-toepassingen.',
            ]);
            return $factoryGroupCategory->id;
        }
    ];
});
