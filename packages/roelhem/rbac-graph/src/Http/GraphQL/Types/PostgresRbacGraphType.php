<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-07-18
 * Time: 02:46
 */

namespace Roelhem\RbacGraph\Http\GraphQL\Types;

use Rebing\GraphQL\Support\Type as GraphQLType;

class PostgresRbacGraphType extends GraphQLType
{

    protected $attributes = [
        'name' => 'PostgresRbacGraph',
        'description' => 'An implementation of the RbacGraph, based around the capabilities of PostgreSQL-queries.'
    ];

    public function interfaces()
    {
        return [
            \GraphQL::type('RbacGraph')
        ];
    }

    public function fields()
    {
        return \GraphQL::type('RbacGraph')->getFields();
    }
}