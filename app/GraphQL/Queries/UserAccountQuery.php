<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 01:14
 */

namespace App\GraphQL\Queries;


use App\UserAccount;

class UserAccountQuery extends ModelViewQuery
{


    protected $attributes = [
        'name' => 'userAccount'
    ];

    protected $typeName = 'UserAccount';

    public function query($args, $selectFields)
    {
        return UserAccount::query();
    }

}