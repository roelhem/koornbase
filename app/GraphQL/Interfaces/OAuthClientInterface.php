<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-07-18
 * Time: 17:11
 */

namespace App\GraphQL\Interfaces;


use App\Enums\OAuthClientType;
use App\GraphQL\Fields\IdField;
use App\GraphQL\Fields\Stamps\CreatedAtField;
use App\GraphQL\Fields\Stamps\CreatedByField;
use App\GraphQL\Fields\Stamps\CreatorField;
use App\GraphQL\Fields\Stamps\EditorField;
use App\GraphQL\Fields\Stamps\UpdatedAtField;
use App\GraphQL\Fields\Stamps\UpdatedByField;
use App\OAuth\Client;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InterfaceType;
use Roelhem\RbacGraph\Services\RbacQueryFilter;

class OAuthClientInterface extends InterfaceType
{

    protected $attributes = [
        'name' => 'OAuthClient',
        'description' => 'The `OAuthClient` interface represents the OAuth2 client applications that use the KoornBase API\'s that require authorization.',
        'model' => Client::class,
    ];

    public function fields()
    {
        return [
            'id' => IdField::class,
            'user_id' => [
                'type' => Type::id(),
                'description' => 'The `ID` of the User that manages this client.',
            ],
            'user' => [
                'type' => \GraphQL::type('User'),
                'description' => 'The User that manages this client.',
                'query' => RbacQueryFilter::eagerLoadingContraintGraphQLClosure(),
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of the client.'
            ],
            'secret' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The shared secret between the server and the client.',
            ],
            'type' => [
                'type' => \GraphQL::type('OAuthClientType'),
                'description' => 'The type of client.',
                'selectable' => false,
                'always' => ['redirect','personal_access_client','password_client']
            ],
            'tokens' => [
                'type' => Type::listOf(\GraphQL::type('OAuthToken')),
                'description' => 'The access tokens that belong to this client.',
                'query' => RbacQueryFilter::eagerLoadingContraintGraphQLClosure(),
            ],
            'revoked' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'The server will deny all request from clients where this value is `true`.'
             ],
            'created_at' => CreatedAtField::class,
            'created_by' => CreatedByField::class,
            'updated_at' => UpdatedAtField::class,
            'updated_by' => UpdatedByField::class
        ];
    }

    public function resolveType(Client $root)
    {
        switch ($root->type->getValue()) {
            case OAuthClientType::PERSONAL:    return \GraphQL::type('OAuthPersonalClient');
            case OAuthClientType::PASSWORD:    return \GraphQL::type('OAuthPasswordClient');
            case OAuthClientType::CREDENTIALS: return \GraphQL::type('OAuthCredentialsClient');
            case OAuthClientType::AUTH_CODE:   return \GraphQL::type('OAuthAuthCodeClient');
        }
    }

}