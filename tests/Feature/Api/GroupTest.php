<?php

namespace Tests\Feature\Api;

use App\Group;
use App\Person;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    private function asAdmin() {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        return $user;
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAttachPerson()
    {
        $admin = $this->asAdmin();

        // Check if the not-found works properly
        $this->postJson("/api/groups/1/attach" , [])->assertStatus(404);
        $this->postJson("/api/groups/name/attach" , [])->assertStatus(404);

        // Setup the database
        $group = factory(Group::class)->create();
        $person = factory(Person::class)->create();
        $personCollection = factory(Person::class, 3)->create();
        $unusedPerson = factory(Person::class)->create();

        $personId = $person->id;
        $personCollectionIds = $personCollection->pluck('id');

        // Check if empty request is accepted
        $this->postJson("/api/groups/{$group->id}/attach", [])->assertStatus(200);

        // Check if null attachment is accepted
        $this->postJson("/api/groups/{$group->id}/attach", [
            'persons' => null
        ])->assertStatus(200);

        // Check if empty attachment is accepted
        $this->postJson("/api/groups/{$group->id}/attach", [
            'persons' => []
        ])->assertStatus(200);

        // Check if single attachment is accepted
        $this->postJson("/api/groups/{$group->id}/attach", [
            'persons' => [$personId]
        ])->assertStatus(200);

        // Check if multiple attachment is accepted
        $this->postJson("/api/groups/{$group->slug}/attach", [
            'persons' => $personCollectionIds
        ])->assertStatus(200);

        // Check if double attachment is not accepted
        $this->postJson("/api/groups/{$group->id}/attach", [
            'persons' => [$personId]
        ])->assertStatus(422);

        // Check wrong input
        $this->postJson("/api/groups/{$group->id}/attach", [
            'persons' => $unusedPerson->id
        ])->assertStatus(422);
        $this->postJson("/api/groups/{$group->id}/attach", [
            'persons' => [$unusedPerson->name]
        ])->assertStatus(422);

        // Check if the changes were accepted
        $expectedPersonIds = $personCollectionIds;
        $expectedPersonIds[] = $personId;

        $persons = $group->persons;
        $this->assertCount(count($expectedPersonIds), $persons);
        foreach ($persons as $person) {
            $this->assertNotEquals($person->id, $unusedPerson->id);
            $this->assertContains($person->id, $expectedPersonIds);
        }
    }

    /**
     * Tests the basic usage of the detach endpoint for detaching persons
     */
    public function testPersonDetach()
    {
        $admin = $this->asAdmin();

        // Check when group does not exist
        $this->postJson("/api/groups/1/detach")->assertStatus(404);

        // Data structure setup
        $group = factory(Group::class)->create();

        $personNotInGroup = factory(Person::class)->create();
        $personStayInGroup = factory(Person::class)->create();
        $personToDetach = factory(Person::class)->create();
        $personCollectionToDetach = factory(Person::class, 3)->create();

        $group->persons()->attach($personStayInGroup->id);
        $group->persons()->attach($personToDetach->id);
        $group->persons()->attach($personCollectionToDetach->pluck('id'));

        // Check if empty request is accepted
        $this->postJson("/api/groups/{$group->id}/detach", [])->assertStatus(200);

        // Check if null is accepted
        $this->postJson("/api/groups/{$group->id}/detach", [
            'persons' => null
        ])->assertStatus(200);

        // Check if empty array is accepted
        $this->postJson("/api/groups/{$group->id}/detach", [
            'persons' => []
        ])->assertStatus(200);

        // Check if single person is accepted
        $this->postJson("/api/groups/{$group->id}/detach", [
            'persons' => [$personToDetach->id]
        ])->assertStatus(200);

        // Check if multiple person is accepted
        $this->postJson("/api/groups/{$group->id}/detach", [
            'persons' => $personCollectionToDetach->pluck('id')
        ])->assertStatus(200);

        // Check if error on trying to detach an unattached person
        $this->postJson("/api/groups/{$group->id}/detach", [
            'persons' => [$personNotInGroup->id],
        ])->assertStatus(422);

        // Check if error on giving the name only.
        $this->postJson("/api/groups/{$group->id}/detach", [
            'persons' => [$personNotInGroup->name],
        ])->assertStatus(422);


        // Check if the changes were accepted
        $resultPersons = $group->persons;

        $this->assertCount(1, $resultPersons);
        $this->assertEquals($personStayInGroup->id, $resultPersons[0]->id);
    }

    /**
     * Tests the basic usage of the sync endpoint to sync the attached persons
     */
    public function testPersonSync()
    {
        $admin = $this->asAdmin();

        // Database structure
        $group = factory(Group::class)->create();
        $persons = factory(Person::class, 4)->create();

        // Check initial state
        $this->assertPersonIndexesInGroup([], $persons, $group->id);

        // Check if some wrong inputs are not accepted.
        $this->postJson("/api/groups/{$group->id}/sync", [
            'persons' => null
        ])->assertStatus(422);
        $this->postJson("/api/groups/{$group->id}/sync", [
            'persons' => [-1]
        ])->assertStatus(422);
        $this->postJson("/api/groups/{$group->id}/sync", [
            'persons' => 'bla',
        ])->assertStatus(422);
        $this->postJson("/api/groups/{$group->id}/sync", [
            'withoutDetaching' => 12,
        ])->assertStatus(422);

        // Adds persons 0, 1 and 2 with a sync request
        $this->postJson("/api/groups/{$group->id}/sync", [
            'persons' => [$persons[0]->id, $persons[1]->id, $persons[2]->id]
        ])->assertStatus(200);
        $this->assertPersonIndexesInGroup([0,1,2], $persons, $group->id);

        // Remove person 0 with a sync request
        $this->postJson("/api/groups/{$group->id}/sync", [
            'persons' => [$persons[1]->id, $persons[2]->id]
        ])->assertStatus(200);
        $this->assertPersonIndexesInGroup([1,2], $persons, $group->id);

        // Remove 1 and insert 3 with one sync request.
        $this->postJson("/api/groups/{$group->id}/sync", [
            'persons' => [$persons[2]->id, $persons[3]->id]
        ])->assertStatus(200);
        $this->assertPersonIndexesInGroup([2,3], $persons, $group->id);

        // Check if empty request is accepted (and nothing is done in this case).
        $this->postJson("/api/groups/{$group->id}/sync", [])->assertStatus(200);
        $this->assertPersonIndexesInGroup([2,3], $persons, $group->id);

        // Sync without detach request
        $this->postJson("/api/groups/{$group->id}/sync", [
            'persons' => [$persons[1]->id, $persons[2]->id],
            'withoutDetaching' => true
        ])->assertStatus(200);
        $this->assertPersonIndexesInGroup([1,2,3], $persons, $group->id);

        // Sync empty without detach
        $this->postJson("/api/groups/{$group->id}/sync", [
            'persons' => [],
            'withoutDetaching' => true
        ])->assertStatus(200);
        $this->assertPersonIndexesInGroup([1,2,3], $persons, $group->id);

        // Clear group with Sync request
        $this->postJson("/api/groups/{$group->id}/sync", [
            'persons' => []
        ])->assertStatus(200);
        $this->assertPersonIndexesInGroup([], $persons, $group->id);

    }

    /**
     * Asserts that the persons with an index in the indexes array are in the group with group_id.
     *
     * Also asserts that the persons with an index that is not in the indexes array is not in group_id.
     *
     * @param $indexes
     * @param $persons
     * @param $group_id
     */
    protected function assertPersonIndexesInGroup($indexes, $persons, $group_id) {
        foreach ($persons as $index => $person) {
            if(in_array($index, $indexes)) {
                $this->assertPersonInGroup($person->id, $group_id, "INDEX[$index]");
            } else {
                $this->assertPersonNotInGroup($person->id, $group_id, "INDEX[$index]");
            }
        }
    }

    /**
     * Asserts if a person with person_id is in a group with group_id
     *
     * @param $person_id
     * @param string $message
     * @param $group_id
     */
    protected function assertPersonInGroup($person_id, $group_id, $message = '') {
        $this->assertDatabaseHas('person_group', [
            'person_id' => $person_id,
            'group_id' => $group_id,
        ]);
    }

    /**
     * Asserts if a person with person_id is not in a group with group_id
     *
     * @param $person_id
     * @param string $message
     * @param $group_id
     */
    protected function assertPersonNotInGroup($person_id, $group_id, $message = '') {
        $this->assertDatabaseMissing('person_group', [
            'person_id' => $person_id,
            'group_id' => $group_id,
        ]);
    }
}
