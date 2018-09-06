<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-09-18
 * Time: 13:23
 */

namespace App\Http\GraphQL\Types\Inputs\Filters;


use GraphQL\Type\Definition\Type;

class UserFilterType extends FilterType
{

    public function filters()
    {
        return [
            'personId' => [
                'type' => Type::id(),
                'description' => 'Filter the users that belong to the person that has the provided `ID`.'
            ],

            'name' => [
                'type' => Type::string(),
                'description' => 'Filters the users that have a name that contains the given string.'
            ],

            'withAnyId' => [
                'type' => Type::listOf(Type::id()),
                'description' => 'Only gives the users that have an `ID` in the provided array of `ID`s.'
            ]
        ];
    }

}