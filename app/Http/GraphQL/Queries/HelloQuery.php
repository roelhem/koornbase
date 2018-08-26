<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 17:34
 */

namespace App\Http\GraphQL\Queries;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class HelloQuery extends Query
{

    protected $attributes = [
        'name' => 'Hello',
        'description' => 'Returns Hello World!',
    ];

    public function type()
    {
        return Type::string();
    }

    public function resolve() {
        return "Hello World!";
    }

}