<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17/11/2018
 * Time: 01:11
 */

namespace App\Actions\Models\Update;


use Roelhem\GraphQL\Facades\GraphQL;

class UpdateOAuthClientAction extends AbstractUpdateAction
{
    public function args()
    {
        return [
            'id' => [
                'type' => GraphQL::type('ID!'),
                'description' => 'The `ID` of the OAuthClient that should be updated.',
                'rules' => ['required','exists:oauth_clients,id'],
            ],
            'name' => [
                'type' => GraphQL::type('String'),
                'description' => 'The new name of the client. If this value is unspecified or `null`, the old name will be used.',
                'rules' => ['nullable','string','max:255'],
            ],
            'redirect' => [
                'type' => GraphQL::type('String'),
                'description' => 'The new URL to which an User should be redirected after it authorized the client. If this value is unspecified or `null`, the redirect-URL will be used.',
                'rules' => ['nullable','url']
            ]
        ];
    }
}