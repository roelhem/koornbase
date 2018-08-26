<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-08-18
 * Time: 02:02
 */

namespace App\Http\GraphQL\Types;


use App\Pivots\PersonGroup;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;


class PersonGroupConnectionType extends GraphQLType
{

    protected $attributes = [
        'name' => 'PersonGroupConnection',
        'description' => 'A connection between a Person and a Group. You can view this as a \'membership\' of a person to a group.',
        'model' => PersonGroup::class
    ];

    public function fields()
    {
        return [
            'person_id' => [
                'description' => 'The `ID` of the person that is connected to a group.',
                'type' => Type::nonNull(Type::id()),
            ],
            'person' => [
                'description' => 'The `Person` that is connected to a group with this connection.',
                'type' => GraphQL::type('Person'),
            ],
            'group_id' => [
                'description' => 'The `ID` of the group to which the person is connected.',
                'type' => Type::nonNull(Type::id()),
            ],
            'group' => [
                'description' => 'The `Group` this is connected to a person with this connection.',
                'type' => GraphQL::type('Group'),
            ]
        ];
    }

}