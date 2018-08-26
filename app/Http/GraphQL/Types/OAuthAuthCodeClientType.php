<?php

namespace App\Http\GraphQL\Types;

use App\Http\GraphQL\Interfaces\OAuthClientInterface;
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
            'authCodes' => [
                'type' => Type::listOf(\GraphQL::type('OAuthAuthCode')),
                'description' => 'The authorization codes of the Users that authorized this `OAuthAuthCodeClient`.'
            ]
        ], $clientInterface->getFields());
    }
}