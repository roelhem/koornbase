<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 23:45
 */

namespace Roelhem\RbacGraph\Http\GraphQL\Types;



use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Roelhem\RbacGraph\Database\Edge;


class RbacEdgeType extends GraphQLType
{

    protected $attributes = [
        'name' => 'RbacEdge',
        'description' => 'An edge in the rbac-graph.',
        'model' => Edge::class
    ];

    /** @inheritdoc */
    public function fields()
    {
        return [
            'parent_id' => [
                'type' => Type::int(),
                'description' => 'The `ID` of the node on the parent side of this edge.'
            ],

            'parent' => [
                'type' => \GraphQL::type('RbacNode'),
                'description' => 'The node on the parent side of this edge.'
            ],

            'child_id' => [
                'type' => Type::int(),
                'description' => 'The `ID` of the node on the child side of this edge.'
            ],

            'child' => [
                'type' => \GraphQL::type('RbacNode'),
                'description' => 'The node on the child side of this edge.'
            ],

            // Timestamps
            'created_at' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'The moment on which this edge was created.'
            ],
            'updated_at' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'The moment on which this edge was updated.'
            ],
        ];
    }

}