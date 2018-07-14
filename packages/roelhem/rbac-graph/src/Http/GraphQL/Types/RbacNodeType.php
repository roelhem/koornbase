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

class RbacNodeType extends GraphQLType
{

    protected $attributes = [
        'name' => 'RbacSuperRole'
    ];

    public function interfaces()
    {
        return [
            GraphQL::type('RbacNode')
        ];
    }

    public function fields()
    {
        return array_merge([

        ], GraphQL::type('RbacNode')->getFields());
    }

}