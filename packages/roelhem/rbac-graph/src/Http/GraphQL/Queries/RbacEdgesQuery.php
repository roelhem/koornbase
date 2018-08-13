<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 22:20
 */

namespace Roelhem\RbacGraph\Http\GraphQL\Queries;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Roelhem\RbacGraph\Database\Edge;

class RbacEdgesQuery extends Query
{

    protected $attributes = [
        'name' => 'edges'
    ];

    public function type()
    {
        return Type::listOf(\GraphQL::type('RbacEdge'));
    }

    public function resolve()
    {
        return Edge::all();
    }

}