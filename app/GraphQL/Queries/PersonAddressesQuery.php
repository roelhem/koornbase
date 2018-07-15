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

class PersonAddressesQuery extends ModelListQuery
{

    protected $attributes = [
        'name' => 'PersonAddresses query'
    ];

    protected $typeName = 'PersonAddress';

    /** @inheritdoc */
    public function query($args, $selectFields)
    {
        return PersonAddress::query();
    }


}