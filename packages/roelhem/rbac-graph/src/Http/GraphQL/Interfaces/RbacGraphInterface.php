<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-07-18
 * Time: 02:20
 */

namespace Roelhem\RbacGraph\Http\GraphQL\Interfaces;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InterfaceType;
use Roelhem\RbacGraph\Contracts\Graphs\Graph;
use Roelhem\RbacGraph\Database\DatabaseGraph;

class RbacGraphInterface extends InterfaceType
{

    protected $attributes = [
        'name' => 'RbacGraph',
        'description' => 'A graph that can be used to check if an User is authorized for some specific tasks.'
    ];

    public function fields()
    {
        return [
            'nodes' => [
                'type' => Type::listOf(\GraphQL::type('RbacNode')),
                'description' => 'A list of all the Nodes in this Graph.',
                'resolve' => function(Graph $root) {
                    return $root->getNodes();
                }
            ],
            'node' => [
                'type' => \GraphQL::type('RbacNode'),
                'description' => 'Finds a Node in the Graph that has a matching `ID`.',
                'args' => [
                    'id' => [
                        'type' => Type::nonNull(Type::id()),
                        'description' => 'The `ID` of the Node you want to find.'
                    ]
                ]
            ],
            'edges' => [
                'type' => Type::listOf(\GraphQL::type('RbacEdge')),
                'description' => 'A list of all the Edges between the Nodes in this Graph.',
                'resolve' => function(Graph $root) {
                    return $root->getEdges();
                }
            ],
            'edge' => [
                'type' => \GraphQL::type('RbacEdge'),
                'description' => 'Finds the Edge between the two Nodes with the specified `ID`s.',
                'args' => [
                    'parentId' => [
                        'type' => Type::nonNull(Type::id()),
                        'description' => 'The `ID` of the Node at the parent-side of the Edge you want to find.'
                    ],
                    'childId' => [
                        'type' => Type::nonNull(Type::id()),
                        'description' => 'The `ID` of the Node at the child-side of the Edge you want to find.'
                    ]
                ]
            ],
            'assignments' => [
                'type' => Type::listOf(\GraphQL::type('RbacAssignment')),
                'description' => 'A list of all the Assignments, which are connecting the Nodes inside the Graph to Models that are outside of the Graph.',
                'resolve' => function(Graph $root) {
                    return $root->getAssignments();
                }
            ]
        ];
    }

    public function resolveType(Graph $graph)
    {
        if($graph instanceof DatabaseGraph) {
            return \GraphQL::type('PostgresRbacGraph');
        }
    }

}