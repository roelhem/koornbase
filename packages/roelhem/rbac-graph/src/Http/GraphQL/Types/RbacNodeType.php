<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 21:11
 */

namespace Roelhem\RbacGraph\Http\GraphQL\Types;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Roelhem\RbacGraph\Database\Node;
use Roelhem\RbacGraph\Enums\NodeType;

class RbacNodeType extends GraphQLType
{


    protected $type;

    public function __construct($type, $attributes = [])
    {
        $this->type = NodeType::by($type);
        parent::__construct($attributes);
    }

    public function attributes()
    {
        return [
            'name' => $this->type->getGraphQLTypeName(),
            'description' => $this->type->getDescription(),
        ];
    }

    public function interfaces()
    {
        $res = [
            GraphQL::type('RbacNode')
        ];

        if($this->type->allowAssignment()) {
            $res[] = GraphQL::type('RbacAssignableNode');
        }

        return $res;
    }

    public function fields()
    {
        return array_merge(
            $this->type->getGraphQLFields(),
            GraphQL::type('RbacNode')->getFields(),
            $this->assignmentFields()
        );
    }

    public function assignmentFields()
    {
        if(!$this->type->allowAssignment()) {
            return [];
        }

        return [
            'assignments' => [
                'type' => Type::listOf(GraphQL::type('RbacAssignment')),
                'description' => 'A list of all the assignments connected to this Node.',
            ]
        ];
    }

}