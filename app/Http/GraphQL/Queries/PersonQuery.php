<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 00:39
 */

namespace App\Http\GraphQL\Queries;


use App\Person;

class PersonQuery extends ModelViewQuery
{

    protected $modelClass = Person::class;

}