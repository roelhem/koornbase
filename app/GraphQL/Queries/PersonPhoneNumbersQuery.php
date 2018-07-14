<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 14:46
 */

namespace App\GraphQL\Queries;


use App\PersonPhoneNumber;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class PersonPhoneNumbersQuery extends Query
{


    protected $attributes = [
        'name' => 'PersonPhoneNumbers query'
    ];

    public function type() {
        return Type::listOf(GraphQL::type('PersonPhoneNumber'));
    }

    public function resolve() {
        return PersonPhoneNumber::all();
    }

}