<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 01:15
 */

namespace App\GraphQL\Queries;


use App\User;

class UserQuery extends ModelViewQuery
{

    protected $modelClass = User::class;

}