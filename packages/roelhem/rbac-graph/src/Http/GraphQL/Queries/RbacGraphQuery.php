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
use Roelhem\RbacGraph\Database\DatabaseGraph;
use Roelhem\RbacGraph\Database\Edge;

class RbacGraphQuery extends Query
{

    protected $attributes = [
        'name' => 'rbacGraph',
        'description' => 'Entry-point to explore the RbacGraph with GraphQL.'
    ];

    public function type()
    {
        return \GraphQL::type('RbacGraph');
    }

    public function resolve()
    {
        return resolve(DatabaseGraph::class);
    }

}