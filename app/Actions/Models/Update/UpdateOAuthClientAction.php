<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17/11/2018
 * Time: 01:11
 */

namespace App\Actions\Models\Update;


use App\OAuth\Client;
use Illuminate\Auth\Access\AuthorizationException;
use Roelhem\Actions\Contracts\ActionContext;
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
                'rules' => ['nullable','string','max:255','unique_or_same:oauth_clients'],
            ],
            'redirect' => [
                'type' => GraphQL::type('String'),
                'description' => 'The new URL to which an User should be redirected after it authorized the client. If this value is unspecified or `null`, the redirect-URL will be used.',
                'rules' => ['nullable','url']
            ]
        ];
    }

    /** @inheritdoc */
    public function handle($validArgs = [], ?ActionContext $context = null)
    {
        $id = array_get($validArgs,'id');
        /** @var Client $client */
        $client = Client::findOrFail($id);

        if($client->revoked) {
            throw new \Exception("Can't update a revoked OAuthClient");
        }

        // Check if the update ability for this model is allowed in this context.
        if(!$context->can('update', $client)) {
            throw new AuthorizationException("Not allowed to update this model.");
        };

        // Fill the values of the model
        $client->fill(array_except($validArgs,'id'));

        // Save the changes
        $client->saveOrFail();

        // return the updated model as the result of this action.
        return $client;
    }
}