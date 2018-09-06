<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-09-18
 * Time: 13:40
 */

namespace App\Http\GraphQL\Types\Inputs\Filters;


use GraphQL\Type\Definition\Type;

class OAuthClientFilterType extends FilterType
{

    public function filters()
    {
        return [
            'type' => [
                'type' => \GraphQL::type('OAuthClientType'),
                'description' => 'Filters the OAuthClient with the provided client type.'
            ],

            'anyType' => [
                'type' => Type::listOf(\GraphQL::type('OAuthClientType')),
                'description' => 'Filters the OAuthClients that are of one of the given types.'
            ],

            'revoked' => [
                'type' => Type::boolean(),
                'description' => 'Filters the OAuthClients that are revoked (or not).'
            ],

            'search' => [
                'type' => Type::string(),
                'description' => 'Filters the OAuthClients that contain the provided string in their name.'
            ]
        ];
    }

}