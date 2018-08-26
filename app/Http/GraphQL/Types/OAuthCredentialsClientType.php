<?php

namespace App\Http\GraphQL\Types;

use App\Http\GraphQL\Interfaces\OAuthClientInterface;
use App\OAuth\Client;
use Rebing\GraphQL\Support\Type as GraphQLType;

class OAuthCredentialsClientType extends GraphQLType
{
    protected $attributes = [
        'name' => 'OAuthCredentialsClient',
        'description' => 'A client application for machine-to-machine communication. This makes it possible for an external application to use some KoornBase API\'s without the need of an User to log-in.',
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

        return $clientInterface->getFields();
    }
}