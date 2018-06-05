<?php

use Faker\Generator as Faker;
use \libphonenumber\PhoneNumberUtil;
use \libphonenumber\PhoneNumberType;
use \libphonenumber\PhoneNumberFormat;
use \libphonenumber\PhoneNumber;

$factory->define(App\PersonPhoneNumber::class, function (Faker $faker) {

    return [
        'person_id' => function() {
            $person = \App\Person::query()->inRandomOrder()->first();
            if($person instanceof \App\Person) {
                return $person->id;
            } else {
                return factory(\App\Person::class)->create()->id;
            }
        },
        'label' => $faker->word,
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