<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 22:49
 */

namespace Roelhem\RbacGraph\Http\GraphQL\Interfaces;

use GraphQL\Error\Error;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InterfaceType;
use Roelhem\RbacGraph\Database\Node;
use Roelhem\RbacGraph\Enums\NodeType;

class RbacNodeInterface extends InterfaceType
{

    protected $attributes = [
        'name' => 'RbacNode',
        'description' => 'A node in the rbac-graph.',
        'model' => Node::class
    ];



    /** @inheritdoc */
    public function fields()
    {
        return [
            // Normal fields
            'id' => [
                'type' => Type::nonNull(Type::int())
            ],
            'name' => [
                'type' => Type::nonNull(Type::string())
            ],
            'type' => [
                'type' => \GraphQL::type('RbacNodeType')
            ],
            'title' => [
                'type' => Type::string()
            ],
            'description' => [
                'type' => Type::string()
            ],
            // Timestamps
            'created_at' => [
                'type' => \GraphQL::type('DateTime')
            ],
            'updated_at' => [
                'type' => \GraphQL::type('DateTime')
            ],

            // RELATIONS

            // Nodes
            'parents' => [
                'type' => Type::listOf(\GraphQL::type('RbacNode'))
            ],

            'children' => [
                'type' => Type::listOf(\GraphQL::type('RbacNode'))
            ],

            'ancestors' => [
                'type' => Type::listOf(\GraphQL::type('RbacNode'))
            ],

            'offspring' => [
                'type' => Type::listOf(\GraphQL::type('RbacNode'))
            ],

            // Edges
            'incomingEdges' => [
                'type' => Type::listOf(\GraphQL::type('RbacEdge')),
                'description' => 'The edges that enter this node. (This are all the edges with this node on the child side.)'
            ],

            'outgoingEdges' => [
                'type' => Type::listOf(\GraphQL::type('RbacEdge')),
                'description' => 'The edges that leave this node. (This are all the edges with this node on the parent side.)'
            ],

            // Paths
            'incomingPaths' => [
                'type' => Type::listOf(\GraphQL::type('RbacPath')),
                'description' => 'All the paths that end on this node.'
            ],

            'outgoingPaths' => [
                'type' => Type::listOf(\GraphQL::type('RbacPath')),
                'description' => 'All the paths that start at this node.'
            ]
        ];
    }

    /** @inheritdoc */
    public function resolveType(Node $node)
    {
        try {
            return \GraphQL::type($node->getType()->getGraphQLType());
        } catch (\Throwable $e) {
            return  \GraphQL::type('RbacDefaultNode');
        }

    }

}