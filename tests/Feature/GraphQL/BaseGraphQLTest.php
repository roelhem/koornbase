<?php

namespace Tests\Feature\GraphQL;

use GraphQL\Utils\SchemaPrinter;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BaseGraphQLTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetSchema()
    {
        $this->asSuper();

        echo SchemaPrinter::doPrint(\GraphQL::schema());

        $this->graphql(/** @lang GraphQL */"
        {
            __schema {
                types {
                    name
                    description
                }
            }
        }
        ")->assertStatus(200);
    }
}
