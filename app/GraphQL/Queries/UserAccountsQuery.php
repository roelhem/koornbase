<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:15
 */

namespace App\GraphQL\Queries;

use App\UserAccount;

class UserAccountsQuery extends ModelListQuery
{

    protected $attributes = [
        'name' => 'user_accounts'
    ];

    protected $typeName = 'UserAccount';

    /** @inheritdoc */
    public function query($args, $selectFields)
    {
        return UserAccount::query();
    }

}