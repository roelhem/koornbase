<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 15:15
 */

namespace App\GraphQL\Queries;


use App\PersonAddress;
use GraphQL;
use GraphQL\Type\Definition\Type;

class PersonAddressesQuery extends ModelListQuery
{

    protected $modelClass = PersonAddress::class;



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


            'locality' => [
                'type' => Type::string(),
                'description' => 'Filters the addresses that have a similar locality as the provided string.'
            ],


            'countryCode' => [
                'type' => GraphQL::type('CountryCode'),
                'description' => 'Filters the addresses that have the provided country_code.'
            ]

        ]);
    }


}