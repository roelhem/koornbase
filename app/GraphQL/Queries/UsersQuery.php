<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 17:46
 */

namespace App\GraphQL\Queries;


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
            ]


        ]);
    }

}