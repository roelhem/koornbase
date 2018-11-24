<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 19/11/2018
 * Time: 17:42
 */

namespace App\Http\GraphQL\Types\Models\OAuth;


use App\OAuth\Token;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ModelType;

class OAuthTokenType extends ModelType
{

    public $name = 'OAuthToken';

    public $modelClass = Token::class;

    public $description = "The `OAuthToken`-type represents an OAuth2 access-token known by the KoornBase server.
                           These access tokens are used to authorize requests to the KoornBase API\'s using the
                           OAuth2 protocol.
                           \n\nThis type only contain the meta-data of the access-token, but never the access-token itself.
                           This is because the access-token is considered a shared secret between the `User` and the
                           server. When a new access-token is issued to the user, a `OAuthTokenIssue` object is used,
                           that does contain the accessToken.";

    /** @inheritdoc */
    public function fields()
    {
        return [
            'id' => [
                'description' => "The primary key of the `OAuthToken`, used to identify this token.
                                  \n\nIMPORTANT REMARK: The id is not a normal `ID`, but a string representation of a 
                                  hexadecimal number.",
                'type' => GraphQL::type('String!'),
            ],
            'user' => [
                'description' => 'The `User` that is used in the authorization of a request with this access token.',
                'type' => GraphQL::type('User'),
            ],
            'client' => [
                'description' => 'The `OAuthClient` to whom this access token belongs to.',
                'type' => GraphQL::type('OAuthClient'),
            ],
            'name' => [
                'description' => 'The name of the access token. Can be used to display to the user.',
                'type' => GraphQL::type('String'),
            ],
            'scopes' => [
                'description' => 'The scopes that are granted when this access token is used for authorization.',
                'alias' => 'scope_objects',
                'type' => GraphQL::type('[OAuthScope]'),
            ],
            'revoked' => [
                'description' => 'Shows if this access token was revoked. When revoked, it will always be rejected by the server.',
                'type' => GraphQL::type('Boolean'),
            ],
            'expiresAt' => [
                'description' => 'The `DateTime` when this token will expire.',
                'alias' => 'expires_at',
                'type' => GraphQL::type('DateTime')
            ],
            'expired' => [
                'type' => GraphQL::type('Boolean'),
                'description' => 'Whether or not this access token is expired.',
                'args' => [
                    'at' => [
                        'type' => GraphQL::type('DateTime'),
                        'description' => 'The moment on which you want to check if the token is expired. If this argument was omitted, the current moment will be used instead.',
                    ],
                ],
                'resolve' => function($root, $args) {
                    if($root instanceof Token) {
                        return $root->expired(array_get($args, 'at'));
                    }
                    return false;
                }
            ],
            'isValid' => [
                'type' => GraphQL::type('Boolean'),
                'description' => 'Whether or not this access token is valid, and thus accepted by the server. This field takes the `expiresAt` and the `revoked` fields in account.',
                'args' => [
                    'at' => [
                        'type' => GraphQL::type('DateTime'),
                        'description' => 'The moment on which you want to check if the token is valid. If this argument was omitted, the current moment will be used instead.'
                    ],
                ],
                'resolve' => function($root, $args) {
                    if($root instanceof Token) {
                        return $root->isValid(array_get($args, 'at'));
                    }
                    return false;
                }
            ],
            'modelInfo' => [
                'type' => GraphQL::type('ModelInfo'),
                'description' => "Provides some (server-side) information about the model.",
                'resolve' => function($source) { return $source; },
                'importance' => -255,
            ]
        ];
    }

    /**
     * Overwrites the default interfaces of a `ModelType`. This is because the `id` of an `OAuthToken` is not a
     * normal (integer) id, but a hexadecimal string. Therefore, it will not conform to the default `Model` interface.
     *
     * @return array
     */
    public function interfaces()
    {
        return [];
    }
}