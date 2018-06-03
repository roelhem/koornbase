<?php

namespace Tests\Feature\Finders;

use App\Person;
use App\Services\Finders\PersonFinder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PersonFinderTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testInstance()
    {
        $personFinder = resolve(PersonFinder::class);

        $this->assertInstanceOf(PersonFinder::class, $personFinder);
        $this->assertEquals(Person::class, $personFinder->modelClass());
    }

    public function testAccepts() {
        $personFinder = resolve(PersonFinder::class);

        $person = factory(Person::class)->create();

        $this->assertTrue($personFinder->accepts($person));
        $this->assertTrue($personFinder->accepts($person->id));

        $this->assertFalse($personFinder->accepts('HOI'));
        $this->assertFalse($personFinder->accepts([]));
    }

    public function testFinds() {
        $personFinder = resolve(PersonFinder::class);

        $personA = factory(Person::class)->create();
        $personB = factory(Person::class)->create();

        $personAFound = $personFinder->find($personA->id);
        $personBFound = $personFinder->find($personB);

        $this->assertInstanceOf(Person::class, $personAFound);
        $this->assertInstanceOf(Person::class, $personBFound);

        $this->assertEquals($personA->id, $personAFound->id);
        $this->assertEquals($personB->id, $personBFound->id);
    }
}
