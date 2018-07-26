<?php

namespace App\GraphQL\Types;

use App\GraphQL\Interfaces\OAuthClientInterface;
use App\OAuth\Client;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class OAuthAuthCodeClientType extends GraphQLType
{
    protected $attributes = [
        'name' => 'OAuthAuthCodeClient',
        'description' => 'A client application that first requests the authorization of an `User`. It\'s the most safe and robust client type.',
        'model' => Client::class,
    ];

    public function interfaces()
    {
        return [
            \GraphQL::type('OAuthClient')
        ];
    }

    public function fields()
    {
        /** @var OAuthClientInterface $clientInterface */
        $clientInterface = \GraphQL::type('OAuthClient');

        return array_merge([
            'redirect' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The URL to which the User is redirected after authorizing the client.',
            ],
            'authCodes' => [
                'type' => Type::listOf(\GraphQL::type('OAuthAuthCode')),
                'description' => 'The authorization codes of the Users that authorized this `OAuthAuthCodeClient`.'
            ]
        ], $clientInterface->getFields());
    }
}