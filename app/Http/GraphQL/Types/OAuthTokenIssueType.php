<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 19/11/2018
 * Time: 16:46
 */

namespace App\Http\GraphQL\Types;


use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ObjectType;

class OAuthTokenIssueType extends ObjectType
{
    public $name = "OAuthTokenIssue";

    public $description = "The `OAuthTokenIssue`-type is returned when an new OAuthToken was issued to a user.
                           Afterwards, the value of the `accessToken` field can be used to authorize requests to
                           the KoornBase-server with the OAuth2 protocol. The `accessToken` is only issued once.";

    protected function fields()
    {
        return [
            'accessToken' => [
                'description' => 'The value of the `OAuthToken` that should be send to the server with every request
                                  that you want to authorize using the OAuth2 protocol.',
                'type' => GraphQL::type('String!'),
            ],
            'token' => [
                'description' => 'The `OAuthToken` object of the newly issued access token itself.',
                'type' => GraphQL::type('OAuthToken'),
            ]
        ];
    }
}