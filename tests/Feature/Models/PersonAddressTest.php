<?php

namespace Tests\Feature\Models;

use App\Person;
use App\PersonAddress;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PersonAddressTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Tests if the PersonAddress factory is working properly
     */
    public function testBasicFactory()
    {
        $personAddress = factory(PersonAddress::class)->create();

        $this->assertInstanceOf(PersonAddress::class, $personAddress);
    }

    public function testWithPersonFactory() {

        factory(Person::class, 10)->create();

        $person = factory(Person::class)->create();

        $this->assertInstanceOf(Person::class, $person);

        $personAddress = factory(PersonAddress::class)->create(['person_id' => $person]);

        $this->assertInstanceOf(PersonAddress::class, $personAddress);
        $this->assertEquals($person->id, $personAddress->person_id);

        $this->assertCount(1, $person->addresses()->get());
    }
}
