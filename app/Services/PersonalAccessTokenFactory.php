<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-08-18
 * Time: 06:52
 */

namespace App\Services;

use App\Enums\OAuthClientType;
use App\OAuth\Client;
use Laravel\Passport\PersonalAccessTokenFactory as BaseFactory;
use Laravel\Passport\PersonalAccessTokenResult;

class PersonalAccessTokenFactory extends BaseFactory
{

    /**
     * Creates a new personal access token from a specific client
     *
     * @param Client $client
     * @param mixed $userId
     * @param string $name
     * @param array $scopes
     * @return \Laravel\Passport\PersonalAccessTokenResult
     */
    public function makeWithClient($client, $userId, $name, array $scopes = []) {

        if(!$client->type->is(OAuthClientType::PERSONAL) || $client->revoked) {
            abort(400, 'Client is not allowed to create Personal Access Tokens.');
        }

        $response = $this->dispatchRequestToAuthorizationServer(
            $this->createRequest($client, $userId, $scopes)
        );

        $token = tap($this->findAccessToken($response), function($token) use ($userId, $name) {
            $this->tokens->save($token->forceFill([
                'user_id' => $userId,
                'name' => $name,
            ]));
        });

        return new PersonalAccessTokenResult(
            $response['access_token'], $token
        );
    }


}