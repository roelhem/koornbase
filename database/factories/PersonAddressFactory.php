<?php

use Faker\Generator as Faker;
use CommerceGuys\Addressing\AddressFormat\AddressFormatRepositoryInterface;
use CommerceGuys\Addressing\Country\CountryRepositoryInterface;
use CommerceGuys\Addressing\AddressFormat\AddressField;


$factory->define(\App\PersonAddress::class, function (Faker $faker) {

    $locale = $faker->locale;
    $list = resolve(CountryRepositoryInterface::class)->getList('NL');
    $country_code = substr($locale, -2, 2);
    if (!array_key_exists($country_code, $list)) {
        $country_code = 'NL';
    }

    $localeFaker = \Faker\Factory::create($locale);
    if(!($localeFaker instanceof Faker)) {
        $localeFaker = $faker;
    }

    $addressFormat = resolve(AddressFormatRepositoryInterface::class)->get($country_code);

    $res = [
        'person_id' => function() {
            $person = \App\Person::query()->inRandomOrder()->first();
            if($person instanceof \App\Person) {
                return $person->id;
            } else {
                return factory(\App\Person::class)->create()->id;
            }
        },
        'label' => $faker->word,
        'locale' => $locale,
        'country_code' => $country_code,
        'remarks' => 'Dit adres is automatisch gegenereerd op basis van random gegevens.'
    ];

    $attributes = [
        AddressField::ADMINISTRATIVE_AREA => 'administrative_area',
        AddressField::LOCALITY => 'locality',
        AddressField::DEPENDENT_LOCALITY => 'dependent_locality',
        AddressField::POSTAL_CODE => 'postal_code',
        AddressField::SORTING_CODE => 'sorting_code',
        AddressField::ADDRESS_LINE1 => 'address_line_1',
        AddressField::ADDRESS_LINE2 => 'address_line_2',
        AddressField::ORGANIZATION => 'organisation'
    ];

    $formats = [
        AddressField::ADMINISTRATIVE_AREA => [
            'state' => [],
            'region' => [],
            'district' => []
        ],
        AddressField::LOCALITY => [
            'town' => [],
            'village' => [],
            'city' => [],
        ],
        AddressField::DEPENDENT_LOCALITY => [
            'departmentName' => [],
            'metropolitanCity' => [],
            'township' => [],
            'city' => [],
        ],
        AddressField::POSTAL_CODE => [
            'postcode' => [],
        ],
        AddressField::SORTING_CODE => [
            'numerify' => ['#####']
        ],
        AddressField::ADDRESS_LINE1 => [
            'streetAddress' => []
        ],
        AddressField::ADDRESS_LINE2 => [
            'secondaryAddress' => [],
            'building' => []
        ],
        AddressField::ORGANIZATION => [
            'company' => []
        ]
    ];

    foreach ($addressFormat->getUsedFields() as $usedField) {
        if(array_has($attributes, $usedField)) {

            $attribute = array_get($attributes, $usedField);
            $fakerOptions = array_get($formats, $usedField);

            foreach ($fakerOptions as $format => $params) {
                try {
                    $generatedValue = $localeFaker->format($format, $params);
                    $res[$attribute] = $generatedValue;
                    break;
                } catch (Throwable $exception) {

                }
            }

        }
    }

    return $res;


});