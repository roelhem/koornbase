<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-07-18
 * Time: 02:02
 */

namespace Roelhem\RbacGraph\Http\GraphQL\Types;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Roelhem\RbacGraph\Database\Assignment;

class RbacAssignmentType extends GraphQLType
{

    protected $attributes = [
        'name' => 'RbacAssignment',
        'description' => 'A part of the RbacGraph that connect the Nodes inside the graph with the Models outside the graph.',
        'model' => Assignment::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The primary key, which uniquely identifies the Assignment in the Graph.'
            ],
            'node_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The `ID` of the Node that is connected with this Assignment.'
            ],
            'node' => [
                'type' => \GraphQL::type('RbacAssignableNode'),
                'description' => 'The Node that is connected with this Assignment.'
            ],
            'created_at' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'The moment on which this Assignment was created.'
            ],
            'updated_at' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'The moment on which this Assignment was last edited.'
            ],
        ];
    }

}