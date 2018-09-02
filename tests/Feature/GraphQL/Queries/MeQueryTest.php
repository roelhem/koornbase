<?php

namespace Tests\Feature\GraphQL\Queries;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MeQueryTest extends TestCase
{
    /**
     * A basic test that tests the most simple properties
     *
     * @return void
     */
    public function testBasicBehaviour()
    {
        $user = $this->asSuper();

        $query = /** @lang GraphQL */'{ 
            me { 
                id 
                name 
                person_id 
                created_at 
                created_by 
                updated_at 
                updated_by 
            } 
        }';

        $this->graphql($query)->assertNoErrors()->assertData([
            'me' => [
                'id' => $user->id,
                'name' => $user->name,
                'person_id' => $user->person_id,
                'created_at' => $user->created_at->format(config('graphql.output_formats.datetime')),
                'created_by' => $user->created_by,
                'updated_at' => $user->updated_at->format(config('graphql.output_formats.datetime')),
                'updated_by' => $user->updated_by,
            ]
        ]);
    }
}
