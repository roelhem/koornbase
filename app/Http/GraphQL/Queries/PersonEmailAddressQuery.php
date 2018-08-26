<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 01:20
 */

namespace App\Http\GraphQL\Queries;


use App\PersonEmailAddress;

class PersonEmailAddressQuery extends ModelViewQuery
{

    protected $modelClass = PersonEmailAddress::class;

}