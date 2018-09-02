<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;
use Roelhem\RbacGraph\Contracts\Nodes\Node;
use Roelhem\RbacGraph\Exceptions\RbacGraphException;
use Tests\Helpers\GraphQLTestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- API REQUESTS --------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Uses the headers for a HTTP-request to one of the json-api's.
     */
    public function withApiHeaders()
    {
        $this->defaultHeaders = ['Accept','application/json'];
    }

    /**
     * Sets the passport requests to the provided user
     *
     * @param User $user
     * @return User
     */
    public function asUser($user)
    {
        $this->withApiHeaders();
        Passport::actingAs($user);
        return $user;
    }

    /**
     * Sets for Passport requests as a user with the Super role.
     *
     * @return User
     */
    public function asSuper()
    {
        /** @var Node $super */
        $super = \Rbac::superRole()->getNode();
        /** @var User $user */
        $user = factory(User::class)->create();
        try {
            $user->assignNode($super);
        } catch (RbacGraphException $exception) {
            throw with($exception);
        }


        $this->asUser($user);
        return $user;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- GraphQL REQUESTS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Sends a GraphQL request with the provided query and variables
     *
     * @param string $query
     * @param array|null $variables
     * @param string|null $operationName
     * @param string $endpoint
     * @return GraphQLTestResponse
     */
    public function graphql($query, $variables = null, $operationName = null, $endpoint = '/graphql') {

        // Determine the parameters of the GraphQL-request
        if(is_string($query)) {
            $params = [
                'query' => $query
            ];
        } elseif(is_array($query)) {
            $params = $query;
        } else {
            throw new \InvalidArgumentException("The query variable has to be a string (with the query) or array (with the parameters).");
        }

        // Add the variables to the GraphQL-request if the $variables are set
        if($variables !== null && is_array($variables)) {
            $params[config('graphql.params_key')] = $variables;
        }

        // Add the operation to the GraphQL-request if the $operationName is set
        if($operationName !== null) {
            $params[config('graphql.operation_name_key')] = $operationName;
        }

        $response = $this->postJson($endpoint, $params, ['Accept','application/json']);
        return new GraphQLTestResponse($response);
    }
}
