<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 01:14
 */

namespace App\Http\GraphQL\Queries;


use App\UserAccount;

class UserAccountQuery extends ModelViewQuery
{

    protected $modelClass = UserAccount::class;

}