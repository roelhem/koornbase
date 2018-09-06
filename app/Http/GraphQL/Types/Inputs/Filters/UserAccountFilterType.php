<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-09-18
 * Time: 13:23
 */

namespace App\Http\GraphQL\Types\Inputs\Filters;


use GraphQL\Type\Definition\Type;

class UserAccountFilterType extends FilterType
{

    public function filters()
    {
        return [
            'provider' => [
                'type' => \GraphQL::type('OAuthProvider'),
                'description' => 'Filters the user-accounts from a specific provider.'
            ],

            'userId' => [
                'type' => Type::id(),
                'description' => 'Filters the user-accounts that belong to the user that has the provided id.'
            ]
        ];
    }

}