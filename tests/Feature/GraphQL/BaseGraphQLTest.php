<?php

namespace Tests\Feature\GraphQL;

use Tests\Helpers\GraphQLTestResponse;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BaseGraphQLTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Tests if the GraphQLTestResponse class is working properly
     *
     * @throws
     */
    public function testTestResponse()
    {
        $validQuery = /** @lang GraphQL */'{ __type(name:"Int") { name }}';

        // An unauthenticated GraphQL response
        $unauthenticatedResponse = $this->graphql($validQuery);
        $this->assertInstanceOf(GraphQLTestResponse::class, $unauthenticatedResponse);
        $unauthenticatedResponse->assertErrors()->assertStatus(401);

        // Use the Super-user
        $this->asSuper();

        // An valid response
        $validResponse = $this->graphql($validQuery);
        $this->assertInstanceOf(GraphQLTestResponse::class, $validResponse);
        $validResponse
            ->assertGraphQLResponse()
            ->assertGraphQLNoErrors()
            ->assertNoErrors()
            ->assertData(['__type'=>['name'=>'Int']]);

        // An invalid response
        $wrongResponse = $this->graphql("{wrong}");
        $this->assertInstanceOf(GraphQLTestResponse::class, $wrongResponse);
        $wrongResponse
            ->assertGraphQLResponse()
            ->assertGraphQLErrors()
            ->assertErrors()
            ->assertData(null);
    }
}
