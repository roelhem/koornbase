<?php

namespace App\GraphQL\Types;

use App\GraphQL\Fields\IdField;
use App\GraphQL\Fields\Stamps\CreatedAtField;
use App\GraphQL\Fields\Stamps\UpdatedAtField;
use App\GraphQL\Interfaces\OAuthClientInterface;
use App\OAuth\Client;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Roelhem\RbacGraph\Services\RbacQueryFilter;

class OAuthPasswordClientType extends GraphQLType
{
    protected $attributes = [
        'name' => 'OAuthPasswordClient',
        'description' => 'A client application that can use an `email` and `password` to authorize a User without redirecting. This type of client should only be used for first-party applications that are regularly checked, because it is possible to steal the password a User if the app is malicious.',
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

        return array_merge([], $clientInterface->getFields());
    }
}