<?php

namespace App\GraphQL\Types;

use App\OAuth\Client;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Roelhem\RbacGraph\Services\RbacQueryFilter;

class OAuthAuthCodeType extends GraphQLType
{
    protected $attributes = [
        'name' => 'OAuthAuthCode',
        'description' => 'A code that is generated when a User authorizes an OAuthClient. The client can use this code to retrieve access tokens.',
        'model' => Client::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The authorization code itself (which is also the primary key or `ID`). IMPORTANT REMARK: The value is a string that represents a hexadecimal number.'
            ],
            'user_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The `ID` of the User that authorized the client, and thus created this authorization code.',
            ],
            'user' => [
                'type' => \GraphQL::type('User'),
                'description' => 'The User that authorized the client, and thus created this authorization code.',
                'query' => RbacQueryFilter::eagerLoadingContraintGraphQLClosure(),
            ],
            'client_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The `ID` of the client that was authorized by the User.'
            ],
            'client' => [
                'type' => \GraphQL::type('OAuthClient'),
                'description' => 'The client that was authorized by the User.',
                'query' => RbacQueryFilter::eagerLoadingContraintGraphQLClosure(),
            ],
            'scopes' => [
                'type' => Type::string(),
                'description' => 'The scopes that are available by the client using this authorization code.'
            ],
            'revoked' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Whether or not this authorization code is revoked. When it is revoked, it won\'t be accepted by the server.'
            ],
            'expires_at' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'The moment on which this authorization code will expire. When the value is `null`, the code will never expire.'
            ]
        ];
    }
}