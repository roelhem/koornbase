<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 08:48
 */

namespace App\Http\GraphQLNew\Types;


use CommerceGuys\Addressing\Country\Country;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ObjectType;

class CountryType extends ObjectType
{

    public $name = 'Country';

    public $description = 'The `Country`-type represents all the information about a country.';

    protected function fields()
    {
        return [
            'name' => [
                'description' => 'The full name of this country.',
                'type' => GraphQL::type('String'),
                'args' => [
                    'locale' => [
                        'description' => 'Optional argument that sets the locale in which the name of the country will be returned.',
                        'type' => GraphQL::type('Locale'),
                        'default' => config('app.locale'),
                    ]
                ],
                'resolve' => function(Country $country) {
                    return $country->getName();
                }
            ],
            'code' => [
                'description' => 'The `CountryCode` of this country, which uniquely identifies this `Country`.',
                'type' => GraphQL::type('CountryCode!'),
                'resolve' => function(Country $country) {
                    return $country->getCountryCode();
                }
            ],
            'threeLetterCode' => [
                'type' => GraphQL::type('String'),
                'resolve' => function(Country $country) {
                    return $country->getThreeLetterCode();
                }
            ],
            'numericCode' => [
                'type' => GraphQL::type('String'),
                'resolve' => function(Country $country) {
                    return $country->getNumericCode();
                }
            ],
            'currencyCode' => [
                'type' => GraphQL::type('String'),
                'resolve' => function(Country $country) {
                    return $country->getCurrencyCode();
                }
            ],
            'defaultLocale' => [
                'description' => 'The default locale of the country.',
                'type' => GraphQL::type('Locale'),
                'resolve' => function(Country $country) {
                    return $country->getLocale();
                }
            ]
        ];
    }
}