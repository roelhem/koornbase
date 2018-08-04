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
                'type' => Type::nonNull(Type::id()),
                'description' => 'The primary key, which uniquely identifies a Node in the Graph.'
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of the node, which uniquely identifies the Node in the Graph.'
            ],
            'type' => [
                'type' => \GraphQL::type('RbacNodeType'),
                'description' => 'The type of node.',
            ],
            'title' => [
                'type' => Type::string(),
                'description' => 'The title is a human-readable string that describes the node.'
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'The description of the Node, which gives some extra information about the function of the node.'
            ],
            // Timestamps
            'created_at' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'The moment on which this Node was created.'
            ],
            'updated_at' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'The moment on which this Node was last edited.'
            ],

            // RELATIONS

            // Nodes
            'parents' => [
                'type' => Type::listOf(\GraphQL::type('RbacNode')),
                'description' => 'All the (direct) parent Nodes of this Node. Every Node in this list is connected to this Node with just one Edge, where this Node is on the child-side of the edge.'
            ],

            'children' => [
                'type' => Type::listOf(\GraphQL::type('RbacNode')),
                'description' => 'All the (direct) child Nodes of this Node. Every Node in this list is connected to this Node with just one Edge, were this Node is on the parent-side of the edge.'
            ],

            'ancestors' => [
                'type' => Type::listOf(\GraphQL::type('RbacNode')),
                'description' => 'A list of all the parent Nodes, and the parent Nodes of the parent Nodes etc.'
            ],

            'offspring' => [
                'type' => Type::listOf(\GraphQL::type('RbacNode')),
                'description' => 'A list of all the child Nodes, and the child Nodes of the Child nodes etc.'
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