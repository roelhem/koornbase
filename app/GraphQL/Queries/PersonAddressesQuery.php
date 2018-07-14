<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 15:15
 */

namespace App\GraphQL\Queries;


use App\PersonAddress;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class PersonAddressesQuery extends Query
{

    protected $attributes = [
        'name' => 'PersonAddresses query'
    ];

    public function type() {
        return Type::listOf(GraphQL::type('PersonAddress'));
    }

    public function resolve() {
        return PersonAddress::all();
    }


}