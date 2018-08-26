<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 17:46
 */

namespace App\Http\GraphQL\Queries;


use App\User;
use GraphQL;
use GraphQL\Type\Definition\Type;


class UsersQuery extends ModelListQuery
{

    protected $modelClass = User::class;


    protected function filterArgs()
    {
        return array_merge(parent::filterArgs(), [

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


        ]);
    }

}