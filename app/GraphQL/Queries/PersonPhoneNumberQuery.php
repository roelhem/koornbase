<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 01:14
 */

namespace App\GraphQL\Queries;


use App\PersonPhoneNumber;

class PersonPhoneNumberQuery extends ModelViewQuery
{

    protected $modelClass = PersonPhoneNumber::class;

}