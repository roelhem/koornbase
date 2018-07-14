<?php

namespace App\GraphQL\Queries;

use App\Person;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class PersonsQuery extends Query
{

    protected $attributes = [
        'name' => 'Persons query'
    ];

    public function type() {
        return Type::listOf(GraphQL::type('Person'));
    }

    public function resolve() {
        return Person::all();
    }

}