<?php

namespace App\GraphQL\Types;


use App\GraphQL\Fields\Stamps\CreatedAtField;
use App\GraphQL\Fields\Stamps\UpdatedAtField;
use App\OAuth\Token;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Roelhem\RbacGraph\Services\RbacQueryFilter;

class OAuthTokenType extends GraphQLType
{
    protected $attributes = [
        'name' => 'OAuthToken',
        'description' => 'An \'OAuth2 access token\' that enables an OAuthClient to make requests to the KoornBase API\'s that require authentication.',
        'model' => Token::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The access token itself (which is also the primary key or `ID`). IMPORTANT REMARK: The value is a string that represents a hexadecimal number.'
            ],
            'user_id' => [
                'type' => Type::id(),
                'description' => 'The `ID` of the User that is authenticated when the client uses this access token.',
            ],
            'user' => [
                'type' => \GraphQL::type('User'),
                'description' => 'The User that is authenticated when the client uses this access token.',
                'query' => RbacQueryFilter::eagerLoadingContraintGraphQLClosure(),
            ],
            'client_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The `ID` of the client that uses this access token.'
            ],
            'client' => [
                'type' => \GraphQL::type('OAuthClient'),
                'description' => 'The client that uses this access token.',
                'query' => RbacQueryFilter::eagerLoadingContraintGraphQLClosure(),
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of the access token.'
            ],
            'scopes' => [
                'type' => Type::listOf(Type::string()),
                'description' => 'The available scopes for the client when using this access token.'
            ],
            'revoked' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Whether or not this access token is revoked. When it is revoked, the server won\'t accept any requests that uses this access token.'
            ],

            'created_at' => CreatedAtField::class,
            'updated_at' => UpdatedAtField::class,

            'expires_at' => [
                'type' => \GraphQL::type('DateTime'),
                'description' => 'The moment on which this access token will/was expired. When it is expired, the will server deny any request that uses this access token.'
            ],

            'expired' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Whether or not this access token is expired.',
                'selectable' => false,
                'always' => ['expires_at'],
                'args' => [
                    'at' => [
                        'type' => \GraphQL::type('DateTime'),
                        'description' => 'The moment on which you want to check if the token is expired.'
                    ],
                ],
                'resolve' => function($root, $args) {
                    if($root instanceof Token) {
                        return $root->expired(array_get($args, 'at'));
                    }
                    return false;
                }
            ],

            'is_valid' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Whether or not this access token is valid, and thus accepted by the server. This field takes the `expires_at` and the `revoked` fields in account.',
                'selectable' => false,
                'always' => ['expires_at','revoked'],
                'args' => [
                    'at' => [
                        'type' => \GraphQL::type('DateTime'),
                        'description' => 'The moment on which you want to check if the token is valid.'
                    ],
                ],
                'resolve' => function($root, $args) {
                    if($root instanceof Token) {
                        return $root->isValid(array_get($args, 'at'));
                    }
                    return false;
                }
            ],
        ];
    }
}