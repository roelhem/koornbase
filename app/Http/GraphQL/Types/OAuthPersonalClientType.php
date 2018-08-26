<?php

namespace App\Http\GraphQL\Types;

use App\Http\GraphQL\Interfaces\OAuthClientInterface;
use App\OAuth\Client;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Roelhem\RbacGraph\Services\RbacQueryFilter;

class OAuthPersonalClientType extends GraphQLType
{
    protected $attributes = [
        'name' => 'OAuthPersonalClient',
        'description' => 'A client application that can create tokens for a single user. This is primarily for developers because authenticating to the API\'s for testing and learning is much simpler with this type of client.',
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
            'personalAccessClients' => [
                'type' => Type::listOf(\GraphQL::type('OAuthPersonalAccessClient')),
                'query' => RbacQueryFilter::eagerLoadingContraintGraphQLClosure()
            ],
        ], $clientInterface->getFields());
    }
}