<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 12-07-18
 * Time: 00:03
 */

namespace Roelhem\RbacGraph\Http\GraphQL\Types;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Roelhem\RbacGraph\Database\Path;

class RbacPathType extends GraphQLType
{

    protected $attributes = [
        'name' => 'RbacPath',
        'description' => 'A path in the rbac-graph',
        'model' => Path::class
    ];

    /** @inheritdoc */
    public function fields()
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'The unique `ID` of this path.'
            ],

            // NODES
            // First Node
            'first_node_id' => [
                'type' => Type::id(),
                'description' => 'The `ID` of the first node in this path.'
            ],
            'firstNode' => [
                'type' => GraphQL::type('RbacNode'),
                'description' => 'The first node of this path.',
            ],
            // Last Node
            'last_node_id' => [
                'type' => Type::id(),
                'description' => 'Type `ID` of the last node in this path.'
            ],
            'lastNode' => [
                'type' => GraphQL::type('RbacNode'),
                'description' => 'The last node of this path.'
            ],
            // All nodes
            'nodeIdList' => [
                'type' => Type::listOf(Type::int()),
                'description' => 'A list of all the nodes-ids in this path.',
                'resolve' => function(Path $path) {
                    return $path->getNodeIdList();
                }
            ],
            'nodeList' => [
                'type' => Type::listOf(GraphQL::type('RbacNode')),
                'resolve' => function(Path $path) {
                    return $path->getNodeList();
                }
            ],


            // PATHS
            'first_path_id' => [
                'type' => Type::id(),
                'description' => 'The `ID` of the first sub-path of this path.'
            ],
            'firstPath' => [
                'type' => GraphQL::type('RbacPath'),
                'description' => 'The first sub-path of this path.'
            ],
            'last_path_id' => [
                'type' => Type::id(),
                'description' => 'The `ID` of the second sub-path of this path.'
            ],
            'lastPath' => [
                'type' => GraphQL::type('RbacPath'),
                'description' => 'The last sub-path of this path.'
            ],

            'asFirstDependingPath' => [
                'type' => Type::listOf(GraphQL::type('RbacPath'))
            ],
            'asLastDependingPath' => [
                'type' => Type::listOf(GraphQL::type('RbacPath'))
            ],

            'size' => [
                'type' => Type::id(),
                'description' => 'The total amount of nodes in this path.'
            ],
            'rules_count' => [
                'type' => Type::id(),
                'description' => 'The total amount of rules needed for this path.'
            ]
        ];
    }

}