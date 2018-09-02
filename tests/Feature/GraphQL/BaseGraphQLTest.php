<?php

namespace Tests\Feature\GraphQL;

use GraphQL\Language\Parser;
use Tests\Helpers\GraphQLTestResponse;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BaseGraphQLTest extends TestCase
{

    use RefreshDatabase;

    const INT_TYPE_QUERY = /** @lang GraphQL */'{
        __type(name:"Int") { name }
    }';

    /**
     * Tests if the GraphQLTestResponse class is working properly on a unauthenticated request.
     */
    public function testUnauthenticatedTestResponse()
    {
        // An unauthenticated GraphQL response
        $unauthenticatedResponse = $this->graphql(self::INT_TYPE_QUERY);
        $this->assertInstanceOf(GraphQLTestResponse::class, $unauthenticatedResponse);
        $unauthenticatedResponse->assertErrors()->assertStatus(401);
    }

    /**
     * Tests if the GraphQLTestResponse class is working properly on a valid request.
     */
    public function testValidTestResponse()
    {
        // Use the Super-user
        $this->asSuper();

        // An valid response
        $validResponse = $this->graphql(self::INT_TYPE_QUERY);
        $this->assertInstanceOf(GraphQLTestResponse::class, $validResponse);
        $validResponse
            ->assertGraphQLResponse()
            ->assertGraphQLNoErrors()
            ->assertNoErrors()
            ->assertData(['__type' => ['name' => 'Int']]);

    }

    /**
     * Tests if the GraphQLTestResponse class is working properly on a wrong request.
     */
    public function testWrongTestResponse()
    {
        // Use the Super-user
        $this->asSuper();

        // An invalid response
        $wrongResponse = $this->graphql("{wrong}");
        $this->assertInstanceOf(GraphQLTestResponse::class, $wrongResponse);
        $wrongResponse
            ->assertGraphQLResponse()
            ->assertGraphQLErrors()
            ->assertErrors()
            ->assertData(null);
    }

    /**
     * Tests if the variables are working properly.
     */
    public function testBasicVariables()
    {
        $this->asSuper();

        $query = /** @lang GraphQL */ '
        query testVariablesQuery($name:String!) {
            __type(name:$name) { name }
        }';

        $this->graphql($query, ['name' => 'Int'])->assertNoErrors()->assertData(['__type' => ['name' => 'Int']]);
        $this->graphql($query, ['name' => 'String'])->assertNoErrors()->assertData(['__type' => ['name' => 'String']]);
        $this->graphql($query)->assertErrors();
    }

    /**
     * Tests if fragments are working.
     */
    public function testFragments()
    {
        $this->asSuper();

        $query = /** @lang GraphQL */'
            { 
                __type(name:"Int") {
                    ...testFragment
                }
            }
            
            fragment testFragment on __Type {
                name
            }
        ';

        $this->graphql($query)->assertNoErrors()->assertData(['__type' => ['name' => 'Int']]);
    }

    /**
     * Tests if the operationName is working.
     */
    public function testOperationName()
    {
        $this->asSuper();

        $query = /** @lang GraphQL */'
            query getIntQuery {
                __type(name:"Int") { name }
            }
            
            query getStringQuery {
                __type(name:"String") { name }
            }
        ';

        $this->graphql($query, null, 'getIntQuery')
            ->assertNoErrors()
            ->assertData(['__type' => ['name' => 'Int']]);

        $this->graphql($query, null, 'getStringQuery')
            ->assertNoErrors()
            ->assertData(['__type' => ['name' => 'String']]);

        $this->graphql($query, null, 'getIDQuery')
            ->assertErrors();
    }


}
