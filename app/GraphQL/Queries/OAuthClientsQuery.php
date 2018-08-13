<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 16:39
 */

namespace App\GraphQL\Queries;


use App\OAuth\Client;
use GraphQL\Type\Definition\Type;

class OAuthClientsQuery extends ModelListQuery
{

    protected $modelClass = Client::class;



    protected function getTypeName()
    {
        return 'OAuthClient';
    }



    protected function filterArgs()
    {
        return array_merge(parent::filterArgs(), [


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

        ]);
    }

}