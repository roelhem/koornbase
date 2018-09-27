<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 07:48
 */

namespace App\Http\GraphQLNew\Types\Models;


use App\PersonAddress;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ModelType;

class PersonAddressType extends ModelType
{

    public $modelClass = PersonAddress::class;

    public $description = 'The `PersonAddress`-type models how a certain address belongs to a `Person`.';

    protected function fields()
    {
        return [
            'postalAddress' => [
                'description' => "The `PostalAddress` that is coupled via this model to the `Person`. It can be used
                                  to send mail to the `Person`.",
                'type' => GraphQL::type('PostalAddress'),
                'resolve' => function(PersonAddress $personAddress) {
                    return $personAddress;
                },
                'importance' => 230,
            ]
        ];
    }

    public function interfaces()
    {
        return array_merge(parent::interfaces(), [GraphQL::type('PersonContactEntry')]);
    }

    public function filters()
    {
        return [
            'personId' => [
                'type' => GraphQL::type('ID'),
                'description' => 'Filters the contact entries that belong to the Person with the provided `ID`.'
            ],

            'index' => [
                'type' => GraphQL::type('Int'),
                'description' => 'Filters the contact entries with the provided index value.',
            ],

            'label' => [
                'type' => GraphQL::type('String'),
                'description' => 'Filters the contact entries with a label that is like the provided string.'
            ],


            'locality' => [
                'type' => GraphQL::type('String'),
                'description' => 'Filters the addresses that have a similar locality as the provided string.'
            ],


            'countryCode' => [
                'type' => GraphQL::type('CountryCode'),
                'description' => 'Filters the addresses that have the provided country_code.'
            ]
        ];
    }
}