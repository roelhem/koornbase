<?php

namespace Tests\Feature\GraphQL\Mutations;

use App\Person;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePersonTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicCreatePerson()
    {
        $this->asSuper();

        $query = file_get_contents(__DIR__.'/tests.graphql');

        /** @var Person $person */
        $person = factory(Person::class)->make();

        $variables = [
            'name_first' => $person->name_first,
            'name_initials' => $person->name_initials,
            'name_last' => $person->name_last,
            'name_middle' => $person->name_middle,
            'name_prefix' => $person->name_prefix,
            'name_nickname' => $person->name_nickname,
            'birth_date' => $person->birth_date,
            'remarks' => $person->remarks,
        ];

        $res = $this->graphql($query, $variables, 'testCreatePerson')->assertNoErrors();
        $id = $res->data('createPerson.id');
        $this->assertDatabaseHas('persons', [
            'id' => $id,
            'name_first' => $person->name_first,
            'name_initials' => $person->name_initials,
            'name_last' => $person->name_last,
            'name_middle' => $person->name_middle,
            'name_prefix' => $person->name_prefix,
            'name_nickname' => $person->name_nickname,
            'birth_date' => $person->birth_date,
            'remarks' => $person->remarks,
        ]);
    }
}
