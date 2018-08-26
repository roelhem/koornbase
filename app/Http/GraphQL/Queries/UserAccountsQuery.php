<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:15
 */

namespace App\Http\GraphQL\Queries;

use App\UserAccount;
use GraphQL\Type\Definition\Type;

class UserAccountsQuery extends ModelListQuery
{

    protected $modelClass = UserAccount::class;


    protected function filterArgs()
    {
        return array_merge(parent::filterArgs(), [

            'provider' => [
                'type' => \GraphQL::type('OAuthProvider'),
                'description' => 'Filters the user-accounts from a specific provider.'
            ],

            'userId' => [
                'type' => Type::id(),
                'description' => 'Filters the user-accounts that belong to the user that has the provided id.'
            ]


        ]);
    }

}