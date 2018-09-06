<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-09-18
 * Time: 13:22
 */

namespace App\Http\GraphQL\Types\Inputs\Filters;


use GraphQL\Type\Definition\Type;

class PersonAddressFilterType extends FilterType
{

    public function filters()
    {
        return [

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
                'type' => \GraphQL::type('CountryCode'),
                'description' => 'Filters the addresses that have the provided country_code.'
            ]

        ];
    }

}