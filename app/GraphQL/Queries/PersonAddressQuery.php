<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 01:13
 */

namespace App\GraphQL\Queries;


use App\PersonAddress;

class PersonAddressQuery extends ModelViewQuery
{

    protected $modelClass = PersonAddress::class;

}