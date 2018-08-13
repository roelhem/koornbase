<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 22:14
 */

namespace Roelhem\RbacGraph\Http\GraphQL\Queries;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Roelhem\RbacGraph\Database\Path;

class RbacPathsQuery extends Query
{

    protected $attributes = [
        'name' => 'paths'
    ];

    /** @inheritdoc */
    public function type()
    {
        return Type::listOf(\GraphQL::type('RbacPath'));
    }

    /** @inheritdoc */
    public function resolve()
    {
        return Path::all();
    }

}