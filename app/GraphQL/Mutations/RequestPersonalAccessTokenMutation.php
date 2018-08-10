<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-08-18
 * Time: 05:50
 */

namespace App\GraphQL\Mutations;


use App\Enums\OAuthClientType;
use App\Enums\OAuthScope;
use App\OAuth\Client;
use App\Services\PersonalAccessTokenFactory;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class RequestPersonalAccessTokenMutation extends Mutation
{


    protected $attributes = [
        'name' => 'requestPersonalAccessToken',
        'description' => 'Returns a new Personal Access Token that can be used to experiment with the Koornbeurs API\'s.',
    ];

    public function type()
    {
        return \GraphQL::type('OAuthPersonalAccessTokenResult');
    }

    public function args()
    {
        return [
            'name' => [
                'type' => Type::string(),
                'description' => 'The name for the new Personal Access Token.',
            ],
            'clientId' => [
                'type' => Type::id(),
                'description' => 'The `ID` of the Personal Access Client that should provide the Access Token.',
            ],
            'scopes' => [
                'type' => Type::listOf(\GraphQL::type('OAuthScope')),
                'description' => 'An `Array` of scopes to add to the Access Token.'
            ]
        ];
    }

    public function resolve($root, $args) {

        /** @var PersonalAccessTokenFactory $factory */
        $factory = resolve(PersonalAccessTokenFactory::class);

        // The user-id.
        $userId = \Auth::id();

        // The name
        $name = array_get($args, 'name');
        if(empty($name)) {
            $name = 'Personal Access Token '.str_random(16);
        }

        // The Scopes
        $scopeArg = array_get($args, 'scopes');
        if(empty($scopeArg)) {
            $scopes = [];
        } else {
            $scopes = collect($scopeArg)->map(function(OAuthScope $scope) {
                return $scope->getName();
            })->values()->all();
        }


        // The client-id
        $clientId = array_get($args, 'clientId');

        // Making the result
        if(empty($clientId)) {
            return $factory->make($userId, $name, $scopes);
        } else {
            /** @var Client $client */
            $client = Client::query()->findOrFail($clientId);
            if($client->revoked) {
                abort(400, 'Can\'t get a Access Token from a revoked client.');
            }
            if(!$client->type->is(OAuthClientType::PERSONAL)) {
                abort(400, 'Only Personal Access Clients can provide Personal Access Tokens.');
            }
            return $factory->makeWithClient($client, $userId, $name, $scopes);
        }

    }

}