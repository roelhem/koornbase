<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 08:19
 */

namespace App\Http\GraphQL\Types\Models;


use App\PersonPhoneNumber;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ModelType;

class PersonPhoneNumberType extends ModelType
{

    public $modelClass = PersonPhoneNumber::class;

    public $name = 'PersonPhoneNumber';

    public $description = 'The `PersonPhoneNumber`-type models the connection and usage of a `PhoneNumber` of a `Person`.';

    protected function fields()
    {
        return [
            'phoneNumber' => [
                'description' => "The `PhoneNumber` that is associated to the `Person` via this model.",
                'type' => GraphQL::type('PhoneNumber'),
                'resolve' => function(PersonPhoneNumber $personPhoneNumber) {
                    return $personPhoneNumber->phone_number;
                },
                'importance' => 230,
            ],
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

            'countryCode' => [
                'type' => GraphQL::type('CountryCode'),
                'description' => 'Filters the phone numbers that have the provided countryCode.'
            ]
        ];
    }
}