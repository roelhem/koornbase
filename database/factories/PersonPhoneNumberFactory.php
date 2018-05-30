<?php

use Faker\Generator as Faker;
use \libphonenumber\PhoneNumberUtil;
use \libphonenumber\PhoneNumberType;
use \libphonenumber\PhoneNumberFormat;
use \libphonenumber\PhoneNumber;

$factory->define(App\PersonPhoneNumber::class, function (Faker $faker) {

    return [
        'person_id' => function() {
            return \App\Person::query()->inRandomOrder()->value('id');
        },
        'label' => $faker->word,
        'is_primary' => false,
        'for_emergency' => false,
        'is_mobile' => $faker->boolean,
        'country_code' => $faker->randomElement(['NL','GB','FR','BE','DE','IT','SE','NO','DK','ES','PT','CH','AT']),
        'phone_number' => function($self) {
            $phoneNumberUtil = PhoneNumberUtil::getInstance();

            $country_code = array_get($self, 'country_code','NL');

            if(array_get($self, 'is_mobile', false)) {
                $res = $phoneNumberUtil->getExampleNumberForType($country_code,PhoneNumberType::MOBILE);
            } else {
                $res = $phoneNumberUtil->getExampleNumber($country_code);
            }

            if($res instanceof PhoneNumber) {
                return $phoneNumberUtil->format($res, PhoneNumberFormat::E164);
            } else {
                return $res;
            }
        },
        'remarks' => 'Gegenereerd door een Factory.'
    ];
});

$factory->state(\App\PersonPhoneNumber::class, 'primary', [
    'is_primary' => true,
]);
