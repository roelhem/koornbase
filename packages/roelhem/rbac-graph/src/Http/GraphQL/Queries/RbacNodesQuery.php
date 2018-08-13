<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 21:18
 */

namespace Roelhem\RbacGraph\Http\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use Roelhem\RbacGraph\Database\Node;


/**
 * Class RbacNodesQuery
 * @package Roelhem\RbacGraph\Http\GraphQL\Queries
 */
class RbacNodesQuery extends Query
{

    protected $attributes = [
        'name' => 'nodes'
    ];

    /** @inheritdoc */
    public function type()
    {
        return GraphQL::paginate('RbacNode');
    }

    public function args()
    {
        return [
            'limit' => [
                'type' => Type::int(),
                'defaultValue' => 15
            ],
            'page' => [
                'type' => Type::int(),
                'defaultValue' => 1
            ]
        ];
    }

    /** @inheritdoc */
    public function resolve($root, $args, SelectFields $fields)
    {
        return Node::with($fields->getRelations())->select($fields->getSelect())
            ->paginate($args['limit'], ['*'], 'page', $args['page']);
    }

}