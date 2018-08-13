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

class RbacAssignableNodeInterface extends InterfaceType
{

    protected $attributes = [
        'name' => 'RbacAssignableNode',
        'description' => 'A node in the rbac-graph that can be assigned to models outside of the rbac-graph.',
        'model' => Node::class
    ];



    /** @inheritdoc */
    public function fields()
    {
        $rbacNodeFields = \GraphQL::type('RbacNode')->getFields();

        return array_merge($rbacNodeFields, [
            'assignments' => [
                'type' => Type::listOf(\GraphQL::type('RbacAssignment')),
                'description' => 'A list of all the assignments connected to this Node.',
            ]
        ]);
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