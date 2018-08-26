<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-08-18
 * Time: 06:24
 */

namespace App\Http\GraphQL\Types;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Laravel\Passport\PersonalAccessTokenResult;

class OAuthPersonalAccessTokenResultType extends GraphQLType
{

    protected $attributes = [
        'name' => 'OAuthPersonalAccessTokenResult',
        'description' => 'A type that is returned as a response on a `requestPersonalAccessToken` mutation. Contains the accesToken and token info of the newly created token.',
    ];

    public function fields() {
        return [
            'accessToken' => [
                'type' => Type::string(),
                'description' => 'A `String` containing the accessToken that can be used to authorize requests to the KoornBase API.',
                'resolve' => function($root) {
                    /** @var PersonalAccessTokenResult $root */
                    return $root->accessToken;
                }
            ],
            'token' => [
                'type' => \GraphQL::type('OAuthToken'),
                'description' => 'An instance of `OAuthToken` containing the meta-properties of the provided accessToken.',
                'resolve' => function($root) {
                    /** @var PersonalAccessTokenResult $root */
                    return $root->token;
                }
            ],
        ];
    }


}