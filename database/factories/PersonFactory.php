<?php

use Faker\Generator as Faker;

$factory->define(App\Person::class, function (Faker $faker) {

    $lastName = $faker->lastName();

    $matches = [];
    preg_match('/((?:[a-z]+ )*)(.+)/', $lastName, $matches);
    $name_prefix = trim($matches[1]);
    if(empty($name_prefix)) {
        $name_prefix = null;
    }
    $name_last = trim($matches[2]);

    return [
        'name_first' => $faker->firstName(),
        'name_middle' => function() use ($faker) {
            $names = [];
            while ($faker->boolean(33)) {
                $names[] = $faker->firstName();
            }

            if (count($names) > 0) {
                return implode(' ', $names);
            }

            return null;
        },
        'name_prefix' => $name_prefix,
        'name_last' => $name_last,
        'name_initials' => function($self) {
            $letters = [];
            $name_first = trim(array_get($self, 'name_first',''));
            $name_middle = trim(array_get($self, 'name_middle',''));
            if(strlen($name_first) >= 1) {
                $letters[] = mb_strtoupper(substr($name_first,0,1));
            }

            foreach (explode(' ', $name_middle) as $name) {
                if(strlen($name) >= 1) {
                    $letters[] = mb_strtoupper(substr($name, 0,1));
                }
            }


            return implode($letters);
        },
        'name_nickname' => function($self) use ($faker) {
            $name_first = array_get($self, 'name_first');

            $length = $faker->numberBetween(4,12);

            if(strlen($name_first) > $length) {
                return mb_substr($name_first, 0, $length);
            } else {
                return null;
            }
        },
        'birth_date' => $faker->dateTimeBetween('-30 years', '-15 years'),
        'remarks' => 'Deze Person is met een factory gegenereerd op basis van willekeurige data. (Alleen voor test-toepassingen)'
    ];
});
