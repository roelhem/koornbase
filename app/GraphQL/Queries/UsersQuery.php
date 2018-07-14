<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 17:46
 */

namespace App\GraphQL\Queries;


use App\User;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;


class UsersQuery extends Query
{


    protected $attributes = [
        'name' => 'Users query'
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('User'));
    }

    public function resolve()
    {
        return User::all();
    }

}