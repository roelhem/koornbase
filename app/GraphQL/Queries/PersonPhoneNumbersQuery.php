<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 14:46
 */

namespace App\GraphQL\Queries;

use App\PersonPhoneNumber;
use GraphQL;
use GraphQL\Type\Definition\Type;

class PersonPhoneNumbersQuery extends ModelListQuery
{

    protected $modelClass = PersonPhoneNumber::class;


    protected function filterArgs()
    {
        return array_merge(parent::filterArgs(), [

            'personId' => [
                'type' => Type::id(),
                'description' => 'Filters the contact entries that belong to the Person with the provided `ID`.'
            ],

            'index' => [
                'type' => Type::int(),
                'description' => 'Filters the contact entries with the provided index value.',
            ],

            'label' => [
                'type' => Type::string(),
                'description' => 'Filters the contact entries with a label that is like the provided string.'
            ],

            'countryCode' => [
                'type' => GraphQL::type('CountryCode'),
                'description' => 'Filters the phone numbers that have the provided country_code.'
            ]

        ]);
    }

}