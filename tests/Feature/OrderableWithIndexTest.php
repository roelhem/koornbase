<?php

namespace Tests\Feature;

use App\Person;
use App\PersonEmailAddress;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderableWithIndexTest extends TestCase
{

    public function testExample()
    {
        $person = factory(Person::class)->create();
        factory(PersonEmailAddress::class, 6)->create(['person_id' => $person->id]);

        $emailAddresses = $person->emailAddresses;

        $this->assertCount(6, $emailAddresses);

        for($i = 0; $i < count($emailAddresses); $i++) {
            $this->assertEquals($i, $emailAddresses[$i]->index);
        }

        $ids = $emailAddresses->pluck('id');

        $emailAddresses[0]->swapWithIndex(2);

        $this->assertDatabaseHas('person_email_addresses', ['id' => $ids[0], 'index' => 2]);
        $this->assertDatabaseHas('person_email_addresses', ['id' => $ids[1], 'index' => 1]);
        $this->assertDatabaseHas('person_email_addresses', ['id' => $ids[2], 'index' => 0]);

        $emailAddresses[3]->moveToIndex(5);

        $this->assertDatabaseHas('person_email_addresses', ['id' => $ids[3], 'index' => 5]);
        $this->assertDatabaseHas('person_email_addresses', ['id' => $ids[4], 'index' => 3]);
        $this->assertDatabaseHas('person_email_addresses', ['id' => $ids[5], 'index' => 4]);

        $emailAddresses[1]->delete();

        $this->assertDatabaseHas('person_email_addresses', ['id' => $ids[0], 'index' => 1]);
        $this->assertDatabaseHas('person_email_addresses', ['id' => $ids[2], 'index' => 0]);
        $this->assertDatabaseHas('person_email_addresses', ['id' => $ids[3], 'index' => 4]);
        $this->assertDatabaseHas('person_email_addresses', ['id' => $ids[4], 'index' => 2]);
        $this->assertDatabaseHas('person_email_addresses', ['id' => $ids[5], 'index' => 3]);

        $person->forceDelete();
    }
}
