<?php

namespace Tests\Feature\Api;

use App\Person;
use App\PersonAddress;
use Faker\Factory as FakerFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PersonAddressTest extends TestCase
{

    use RefreshDatabase, UsePassportAsAdmin;

    /**
     * Tests the basic usage of the index endpoint
     */
    public function testBasicIndex()
    {
        $this->asAdmin();

        $this->getJson("/api/person-addresses")->assertStatus(200);
    }

    /**
     * Tests the basic usage of the store endpoint
     */
    public function testBasicStore()
    {
        $this->asAdmin();

        $nlFaker = FakerFactory::create('nl_NL');
        $usFaker = FakerFactory::create('en_US');

        $this->postJson("/api/person-addresses", [])->assertStatus(422);

        $this->postJson("/api/person-addresses", [
            'person' => 0
        ])->assertStatus(422);

        $person = factory(Person::class)->create();

        $this->postJson("/api/person-addresses", [
            'person' => $person->id,
            'label' => 'empty_address',
        ])->assertStatus(422);

        $this->assertEquals(0, $person->addresses()->count());

        // MINIMAL
        $this->postJson("/api/person-addresses", [
            'person' => $person->id,
            'label' => 'address_minimal',
            'address_line_1' => 'Voldersgracht 1',
            'postal_code' => '2611 DM',
            'locality' => 'Delft'
        ], ['Accept','application/json'])->assertStatus(201);

        $this->assertEquals(1, $person->addresses()->count());

        // TOO MUCH
        $this->postJson("/api/person-addresses", [
            'person' => $person->id,
            'label' => 'address_too_much',
            'address_line_1' => 'Voldersgracht 1',
            'postal_code' => '2611 DM',
            'locality' => 'Delft',
            'administrative_area' => 'Zuid-Holland'
        ])->assertStatus(422);

        $this->assertEquals(1, $person->addresses()->count());

        // OTHER COUNTRY
        $this->postJson("/api/person-addresses", [
            'person' => $person->id,
            'country_code' => 'US',
            'label' => 'address_us',
            'address_line_1' => $usFaker->streetAddress,
            'postal_code' => $usFaker->postcode,
            'locality' => $usFaker->city,
            'administrative_area' => $usFaker->state
        ])->assertStatus(201);

        $this->assertEquals(2, $person->addresses()->count());

        $this->postJson("/api/person-addresses", [
            'person' => $person->id,
            'index' => 1,
            'label' => 'address_index_in_middle',
            'address_line_1' => $nlFaker->streetAddress,
            'postal_code' => $nlFaker->postcode,
            'locality' => $nlFaker->city,
        ])->assertStatus(201);

        $this->postJson("/api/person-addresses", [
            'person' => $person->id,
            'index' => null,
            'label' => 'address_index_last',
            'address_line_1' => $nlFaker->streetAddress,
            'postal_code' => $nlFaker->postcode,
            'locality' => $nlFaker->city,
        ])->assertStatus(201);

        $this->assertEquals(4, $person->addresses()->count());

        $this->assertDatabaseHas('person_addresses',[
            'person_id' => $person->id,
            'index' => 0,
            'label' => 'address_minimal'
        ]);

        $this->assertDatabaseHas('person_addresses',[
            'person_id' => $person->id,
            'index' => 1,
            'label' => 'address_index_in_middle'
        ]);

        $this->assertDatabaseHas('person_addresses',[
            'person_id' => $person->id,
            'index' => 2,
            'label' => 'address_us'
        ]);

        $this->assertDatabaseHas('person_addresses',[
            'person_id' => $person->id,
            'index' => 3,
            'label' => 'address_index_last'
        ]);
    }

    /**
     * Tests if the store endpoint makes shure that a label should be unique.
     */
    public function testLabelUniqueStore() {

        $nlFaker = FakerFactory::create('nl_NL');

        $person = factory(Person::class)->create();
        factory(PersonAddress::class)->create([
            'person_id' => $person->id,
            'label' => 'unique_label'
        ]);

        // Fails because unique_label already exists for $person
        $this->postJson("/api/person-addresses", [
            'person' => $person->id,
            'label' => 'unique_label',
            'address_line_1' => $nlFaker->streetAddress,
            'postal_code' => $nlFaker->postcode,
            'locality' => $nlFaker->city
        ])->assertStatus(422);

        // Works, because new_label does not exist for $person
        $this->postJson("/api/person-addresses", [
            'person' => $person->id,
            'label' => 'new_label',
            'address_line_1' => $nlFaker->streetAddress,
            'postal_code' => $nlFaker->postcode,
            'locality' => $nlFaker->city
        ])->assertStatus(201);

        $this->assertDatabaseHas('person_addresses', [
            'person_id' => $person->id,
            'label' => 'new_label'
        ]);


        $otherPerson = factory(Person::class)->create();
        factory(PersonAddress::class)->create([
            'person_id' => $otherPerson->id,
            'label' => 'other_unique_label'
        ]);

        // Fails because other_unique_label already exists for $otherPerson
        $this->postJson("/api/person-addresses", [
            'person' => $otherPerson->id,
            'label' => 'other_unique_label',
            'address_line_1' => $nlFaker->streetAddress,
            'postal_code' => $nlFaker->postcode,
            'locality' => $nlFaker->city
        ])->assertStatus(422);

        // Works, because new_label does not exist for $otherPerson (even though it exists for $person)
        $this->postJson("/api/person-addresses", [
            'person' => $otherPerson->id,
            'label' => 'new_label',
            'address_line_1' => $nlFaker->streetAddress,
            'postal_code' => $nlFaker->postcode,
            'locality' => $nlFaker->city
        ])->assertStatus(201);

        $this->assertDatabaseHas('person_addresses', [
            'person_id' => $otherPerson->id,
            'label' => 'new_label'
        ]);

    }

    /**
     * Tests the basic usage of the show endpoint
     */
    public function testBasicShow()
    {
        $this->asAdmin();

        $this->getJson("/api/person-addresses/0")->assertStatus(404);

        $personAddress = factory(PersonAddress::class)->create();

        $this->getJson("/api/person-addresses/{$personAddress->id}")
            ->assertStatus(200)
            ->assertJson(['data' => ['id' => $personAddress->id]]);
    }

    /**
     * Tests the basic usage of the update endpoint
     */
    public function testBasicUpdate()
    {
        $this->asAdmin();

        $nlFaker = FakerFactory::create('nl_NL');
        $usFaker = FakerFactory::create('en_US');

        $this->putJson("/api/person-addresses/0", [])->assertStatus(404);

        $person = factory(Person::class)->create();
        $personAddress = $person->addresses()->create([
            'label' => 'new_nl_address',
            'locality' => $nlFaker->city,
            'postal_code' => $nlFaker->postcode,
            'address_line_1' => $nlFaker->streetAddress
        ]);

        $this->putJson("/api/person-addresses/{$personAddress->id}", [])->assertStatus(200);

        $this->putJson("/api/person-addresses/{$personAddress->id}", [
            'label' => null
        ])->assertStatus(422);

        $this->assertDatabaseHas('person_addresses', [
            'id' => $personAddress->id,
            'label' => 'new_nl_address'
        ]);

        $this->putJson("/api/person-addresses/{$personAddress->id}", [
            'label' => 'nl_address'
        ])->assertStatus(200);

        $this->assertDatabaseHas('person_addresses', [
            'id' => $personAddress->id,
            'label' => 'nl_address'
        ]);

        $this->putJson("/api/person-addresses/{$personAddress->id}", [
            'postal_code' => '15432654276543'
        ], ['Accept', 'application/json'])->assertStatus(422);

        $this->putJson("/api/person-addresses/{$personAddress->id}", [
            'postal_code' => '1234 AB'
        ])->assertStatus(200);

        $this->assertDatabaseHas('person_addresses', [
            'id' => $personAddress->id,
            'postal_code' => '1234 AB'
        ]);

        $this->putJson("/api/person-addresses/{$personAddress->id}", [
            'country_code' => 'US'
        ])->assertStatus(422);

        $this->putJson("/api/person-addresses/{$personAddress->id}", [
            'country_code' => 'US',
            'administrative_area' => 'New York',
        ])->assertStatus(422);

        $this->putJson("/api/person-addresses/{$personAddress->id}", [
            'country_code' => 'US',
            'administrative_area' => $usFaker->state,
            'postal_code' => $usFaker->postcode
        ])->assertStatus(200);

        $this->assertDatabaseHas('person_addresses', [
            'id' => $personAddress->id,
            'country_code' => 'US',
        ]);

        $this->putJson("/api/person-addresses/{$personAddress->id}", [
            'postal_code' => $usFaker->postcode
        ])->assertStatus(200);

        $this->putJson("/api/person-addresses/{$personAddress->id}", [
            'country_code' => 'NL',
        ])->assertStatus(422);

        $this->putJson("/api/person-addresses/{$personAddress->id}", [
            'country_code' => 'NL',
            'postal_code' => $nlFaker->postcode
        ])->assertStatus(200);

        $this->assertDatabaseHas('person_addresses', [
            'id' => $personAddress->id,
            'country_code' => 'NL',
            'administrative_area' => null
        ]);


    }

    /**
     * Tests the updating of the index via the update endpoint
     */
    public function testIndexUpdate() {

        $this->asAdmin();

        $person = factory(Person::class)->create();
        $personAddress = factory(PersonAddress::class)->create(['person_id' => $person->id]);
        $otherPersonAddress = factory(PersonAddress::class)->create(['person_id' => $person->id]);

        $this->assertDatabaseHas('person_addresses', [
            'id' => $personAddress->id,
            'index' => 0,
        ]);

        $this->assertDatabaseHas('person_addresses', [
            'id' => $otherPersonAddress->id,
            'index' => 1,
        ]);

        $this->putJson("/api/person-addresses/{$personAddress->id}", [
            'index' => 1
        ], ['Accept','application/json'])->assertStatus(200);

        $this->assertDatabaseHas('person_addresses', [
            'id' => $personAddress->id,
            'index' => 1,
        ]);

        $this->assertDatabaseHas('person_addresses', [
            'id' => $otherPersonAddress->id,
            'index' => 0,
        ]);

    }

    /**
     * Tests if the update endpoint validates if the labels are unique
     */
    public function testUniqueLabelUpdate() {

        $this->asAdmin();

        $person = factory(Person::class)->create();
        $personAddresses = factory(PersonAddress::class, 3)->create(['person_id' => $person->id]);


        foreach ($personAddresses as $index => $personAddress) {
            $this->putJson("/api/person-addresses/{$personAddress->id}", [
                'label' => "label_$index"
            ])->assertStatus(200);

            $this->assertDatabaseHas('person_addresses', [
                'id' => $personAddress->id,
                'person_id' => $person->id,
                'label' => "label_$index"
            ]);
        }

        $this->putJson("/api/person-addresses/{$personAddresses[0]->id}", [
            'label' => 'label_0'
        ])->assertStatus(200);

        $this->putJson("/api/person-addresses/{$personAddresses[1]->id}", [
            'label' => 'label_0'
        ])->assertStatus(422);


        $otherPerson = factory(Person::class)->create();
        $otherPersonAddresses = factory(PersonAddress::class, 2)->create(['person_id' => $otherPerson->id]);

        $this->putJson("/api/person-addresses/{$otherPersonAddresses[0]->id}", [
            'label' => 'label_0'
        ])->assertStatus(200);

        $this->assertDatabaseHas('person_addresses', [
            'id' => $otherPersonAddresses[0]->id,
            'person_id' => $otherPerson->id,
            'label' => "label_0"
        ]);

        $this->putJson("/api/person-addresses/{$otherPersonAddresses[1]->id}", [
            'label' => 'label_0'
        ])->assertStatus(422);


    }
}
