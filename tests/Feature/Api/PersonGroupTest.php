<?php

namespace Tests\Feature\Api;

use App\Group;
use App\Person;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PersonGroupTest extends TestCase
{

    use RefreshDatabase;

    private function asAdmin() {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        return $user;
    }

    /**
     * Tests the enpoint to attach a new group to this person.
     *
     * @return void
     */
    public function testGroupAttach()
    {
        $admin = $this->asAdmin();

        $this->postJson('/api/persons/0/attach', [])->assertStatus(404);

        $person = factory(Person::class)->create();

        $this->postJson("/api/persons/{$person->id}/attach", [
            'groups' => [0]
        ])->assertStatus(422);

        $groups = factory(Group::class, 5)->create();
        $this->assertPersonInGroupsWithIndexes($person->id, $groups, []);

        $this->postJson("/api/persons/{$person->id}/attach", [
            'groups' => null
        ])->assertStatus(200);
        $this->assertPersonInGroupsWithIndexes($person->id, $groups, []);

        $this->postJson("/api/persons/{$person->id}/attach", [
            'groups' => []
        ])->assertStatus(200);
        $this->assertPersonInGroupsWithIndexes($person->id, $groups, []);

        $this->postJson("/api/persons/{$person->id}/attach", [
            'groups' => [$groups[0]->id]
        ])->assertStatus(200);
        $this->assertPersonInGroupsWithIndexes($person->id, $groups, [0]);

        $this->postJson("/api/persons/{$person->id}/attach", [
            'groups' => [$groups[1]->slug]
        ])->assertStatus(200);
        $this->assertPersonInGroupsWithIndexes($person->id, $groups, [0,1]);

        $this->postJson("/api/persons/{$person->id}/attach", [
            'groups' => [$groups[2]->id, $groups[3]->id]
        ])->assertStatus(200);
        $this->assertPersonInGroupsWithIndexes($person->id, $groups, [0,1,2,3]);

        $this->postJson("/api/persons/{$person->id}/attach", [
            'groups' => $groups[4]->id
        ])->assertStatus(422);
        $this->postJson("/api/persons/{$person->id}/attach", [
            'groups' => $groups[4]->slug
        ])->assertStatus(422);
        $this->postJson("/api/persons/{$person->id}/attach", [
            'groups' => [$groups[0]->id]
        ])->assertStatus(422);

        $this->assertPersonInGroupsWithIndexes($person->id, $groups, [0,1,2,3]);
    }

    public function testGroupDetach() {
        $admin = $this->asAdmin();

        $person = factory(Person::class)->create();
        $groups = factory(Group::class, 5)->create();
        $person->groups()->attach($groups->pluck('id'));

        $this->postJson("/api/persons/{$person->id}/detach", [])->assertStatus(200);
        $this->postJson("/api/persons/{$person->id}/detach", [
            'groups' => null
        ])->assertStatus(200);
        $this->postJson("/api/persons/{$person->id}/detach", [
            'groups' => []
        ])->assertStatus(200);
        $this->assertPersonInGroupsWithIndexes($person->id, $groups, [0,1,2,3,4]);

        $this->postJson("/api/persons/{$person->id}/detach", [
            'groups' => [0]
        ])->assertStatus(422);
        $this->postJson("/api/persons/{$person->id}/detach", [
            'groups' => ['FOUT']
        ])->assertStatus(422);
        $this->assertPersonInGroupsWithIndexes($person->id, $groups, [0,1,2,3,4]);

        $this->postJson("/api/persons/{$person->id}/detach", [
            'groups' => [$groups[4]->id]
        ])->assertStatus(200);
        $this->assertPersonInGroupsWithIndexes($person->id, $groups, [0,1,2,3]);

        $this->postJson("/api/persons/{$person->id}/detach", [
            'groups' => [$groups[3]->slug]
        ])->assertStatus(200);
        $this->assertPersonInGroupsWithIndexes($person->id, $groups, [0,1,2]);

        $this->postJson("/api/persons/{$person->id}/detach", [
            'groups' => [$groups[2]->id, $groups[1]->slug]
        ])->assertStatus(200);
        $this->assertPersonInGroupsWithIndexes($person->id, $groups, [0]);

        $this->postJson("/api/persons/{$person->id}/detach", [
            'groups' => [$groups[4]->id]
        ])->assertStatus(422);
        $this->postJson("/api/persons/{$person->id}/detach", [
            'groups' => [$groups[3]->slug]
        ])->assertStatus(422);
        $this->assertPersonInGroupsWithIndexes($person->id, $groups, [0]);
    }

    public function testGroupSync() {
        $admin = $this->asAdmin();

        $person = factory(Person::class)->create();
        $groups = factory(Group::class, 4)->create();

        $this->assertPersonInGroupsWithIndexes($person->id, $groups, []);

        // Invalid requests
        $this->postJson("/api/persons/{$person->id}/sync", [
            'groups' => null
        ])->assertStatus(422);
        $this->postJson("/api/persons/{$person->id}/sync", [
            'groups' => [-1]
        ])->assertStatus(422);
        $this->postJson("/api/persons/{$person->id}/sync", [
            'groups' => 'FOUT'
        ])->assertStatus(422);
        $this->postJson("/api/persons/{$person->id}/sync", [
            'withoutDetaching' => 'bla'
        ])->assertStatus(422);

        // Adds persons 0,1 and 2
        $this->postJson("/api/persons/{$person->id}/sync", [
            'groups' => [$groups[0]->id, $groups[1]->id, $groups[2]->id]
        ])->assertStatus(200);
        $this->assertPersonInGroupsWithIndexes($person->id, $groups, [0,1,2]);

        $this->postJson("/api/persons/{$person->id}/sync", [
            'groups' => [$groups[1]->id, $groups[2]->id]
        ])->assertStatus(200);
        $this->assertPersonInGroupsWithIndexes($person->id, $groups, [1,2]);

        $this->postJson("/api/persons/{$person->id}/sync", [
            'groups' => [$groups[2]->id, $groups[3]->id]
        ])->assertStatus(200);
        $this->assertPersonInGroupsWithIndexes($person->id, $groups, [2,3]);

        $this->postJson("/api/persons/{$person->id}/sync", [])->assertStatus(200);
        $this->assertPersonInGroupsWithIndexes($person->id, $groups, [2,3]);

        $this->postJson("/api/persons/{$person->id}/sync", [
            'groups' => [$groups[1]->id, $groups[2]->id],
            'withoutDetaching' => true
        ])->assertStatus(200);
        $this->assertPersonInGroupsWithIndexes($person->id, $groups, [1,2,3]);
        $this->postJson("/api/persons/{$person->id}/sync", [
            'groups' => [],
            'withoutDetaching' => true
        ])->assertStatus(200);
        $this->assertPersonInGroupsWithIndexes($person->id, $groups, [1,2,3]);

        $this->postJson("/api/persons/{$person->id}/sync", [
            'groups' => []
        ])->assertStatus(200);
        $this->assertPersonInGroupsWithIndexes($person->id, $groups, []);
    }

    /**
     * Asserts that the person with $person_id as id has all the groups in the indexes list.
     *
     * @param $person_id
     * @param $groups
     * @param $indexes
     */
    protected function assertPersonInGroupsWithIndexes($person_id, $groups, $indexes) {
        foreach ($groups as $index => $group) {
            if(in_array($index, $indexes)) {
                $this->assertPersonInGroup($person_id, $group->id, "INDEX[$index]");
            } else {
                $this->assertPersonNotInGroup($person_id, $group->id, "INDEX[$index]");
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
