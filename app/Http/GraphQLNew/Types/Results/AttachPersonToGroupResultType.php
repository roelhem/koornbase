<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 19/11/2018
 * Time: 19:28
 */

namespace App\Http\GraphQLNew\Types\Results;


use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ObjectType;

class AttachPersonToGroupResultType extends ObjectType
{

    /**
     * Returns the definitions of the fields of this Type.
     *
     * @return array
     */
    protected function fields()
    {
        return [
            'group' => [
                'type' => GraphQL::type('Group'),
            ],
            'person' => [
                'type' => GraphQL::type('Person'),
            ]
        ];
    }
}