<?php

namespace Tests\Feature\Api;

use App\Http\Resources\Api\MembershipResource;
use App\Membership;
use App\Person;
use App\User;
use Carbon\Carbon;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MembershipTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Use the API as an admin user.
     *
     * @return mixed
     */
    private function asAdmin() {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        return $user;
    }

    /**
     * Tests the basic usage of the index endpoint
     *
     * @return void
     */
    public function testBasicIndex()
    {
        $this->asAdmin();

        $this->get("/api/memberships")->assertStatus(200)
            ->assertJsonCount(0, 'data');

        $memberships = factory(Membership::class, 5)->create();

        $this->get("/api/memberships")->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJson([
                'data' => $memberships->map(function($membership) {
                    return [
                        'id' => $membership->id,
                        'application' => $membership->application->toDateString(),
                        'start' => $membership->start->toDateString(),
                        'end' => $membership->end->toDateString(),
                        'status' => $membership->status
                    ];
                })->all()
            ]);
    }

    /**
     * Tests the basic usage of the store endpoint
     */
    public function testBasicStore()
    {
        $user = $this->asAdmin();
        $this->defaultHeaders = ['Accept', 'application/json'];

        $this->postJson("/api/memberships", [])->assertStatus(422);

        $person = factory(Person::class)->create();

        $this->postJson("/api/memberships", [
            'person' => $person->id
        ])->assertStatus(422);
        $this->postJson("/api/memberships", [
            'person' => $person->id,
            'application' => Carbon::now()->subYear()->subMonths(2)->toDateString(),
            'start' => Carbon::now()->subYear()->toDateString(),
            'end' => Carbon::now()->subMonths(2)->toDateString()
        ])->assertStatus(201);

        $this->assertDatabaseHas('memberships', [
            'person_id' => $person->id,
            'created_by' => $user->id,
            'updated_by' => $user->id
        ]);

        $this->postJson("/api/memberships", [
            'person' => $person->id,
            'application' => Carbon::now()->subYears(2)->toDateString()
        ])->assertStatus(422);
        $this->postJson("/api/memberships", [
            'person' => $person->id,
            'application' => Carbon::now()->subYears(2)->toDateString(),
            'start' => Carbon::now()->subYear()->subMonths(4)->toDateString()
        ])->assertStatus(422);
        $this->postJson("/api/memberships", [
            'person' => $person->id,
            'application' => Carbon::now()->subYears(2)->toDateString(),
            'end' => Carbon::now()->subYear()->subMonths(4)->toDateString()
        ])->assertStatus(201);

        $this->assertEquals(2, $person->memberships()->count());

        $this->postJson("/api/memberships", [
            'person' => $person->id,
            'application' => Carbon::now()->toDateString(),
            'start' => Carbon::now()->subDays(2)->toDateString()
        ])->assertStatus(422);
        $this->postJson("/api/memberships", [
            'person' => $person->id,
            'start' => Carbon::now()->toDateString(),
            'end' => Carbon::now()->subDays(2)->toDateString()
        ])->assertStatus(422);
        $this->postJson("/api/memberships", [
            'person' => $person->id,
            'application' => Carbon::now()->toDateString(),
        ])->assertStatus(201);

        $this->assertEquals(3, $person->memberships()->count());

        $this->postJson("/api/memberships", [
            'person' => $person->id,
            'application' => Carbon::now()->addYear()->toDateString(),
            'start' => Carbon::now()->addYears(2)->toDateString(),
            'end' => Carbon::now()->addYears(2)->toDateString()
        ])->assertStatus(422);

    }


    /**
     * Tests the basic usage of the show endpoint
     */
    public function testBasicShow()
    {
        $this->asAdmin();

        $this->get("/api/memberships/1")->assertStatus(404);

        $membershipA = factory(Membership::class)->create();

        $this->get("/api/memberships/{$membershipA->id}")->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $membershipA->id,
                    'application' => $membershipA->application->toDateString(),
                    'start' => $membershipA->start->toDateString(),
                    'end' => $membershipA->end->toDateString(),
                    'status' => $membershipA->status
                ]
            ]);

        $membershipB = factory(Membership::class)->states('novice')->create();

        $this->get("/api/memberships/{$membershipB->id}")->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $membershipB->id,
                    'application' => $membershipB->application->toDateString(),
                    'start' => null,
                    'end' => null,
                    'status' => $membershipB->status
                ]
            ]);
    }

    /**
     * Tests the basic usage of the update endpoint
     */
    public function testBasicUpdate()
    {
        $user = $this->asAdmin();

        // Update a non-existant membership
        $this->putJson("/api/memberships/1")->assertStatus(404);

        $membership = factory(Membership::class)->create();
        $person = $membership->person;

        // Send an empty update request
        $this->putJson("/api/memberships/{$membership->id}", [])->assertStatus(200);
        $this->assertDatabaseHas('memberships', [
            'id' => $membership->id,
            'person_id' => $membership->person_id,
            'application' => $membership->application->toDateString(),
            'start' => $membership->start->toDateString(),
            'end' => $membership->end->toDateString(),
            'remarks' => $membership->remarks,
        ]);

        // Update just the remarks
        $this->putJson("/api/memberships/{$membership->id}",[
            'remarks' => 'NEWLY UPDATED REMARKS'
        ])->assertStatus(200);

        $this->assertDatabaseHas('memberships', [
            'id' => $membership->id,
            'person_id' => $membership->person_id,
            'application' => $membership->application->toDateString(),
            'start' => $membership->start->toDateString(),
            'end' => $membership->end->toDateString(),
            'remarks' => 'NEWLY UPDATED REMARKS'
        ]);

        // Set start to null
        $this->putJson("/api/memberships/{$membership->id}",[
            'start' => null
        ])->assertStatus(200);

        $this->assertDatabaseHas('memberships', [
            'id' => $membership->id,
            'person_id' => $membership->person_id,
            'application' => $membership->application->toDateString(),
            'start' => null,
            'end' => $membership->end->toDateString()
        ]);

        // Set end to null
        $this->putJson("/api/memberships/{$membership->id}",[
            'end' => null
        ])->assertStatus(200);

        $this->assertDatabaseHas('memberships', [
            'id' => $membership->id,
            'person_id' => $membership->person_id,
            'application' => $membership->application->toDateString(),
            'start' => null,
            'end' => null,
        ]);

        // Do not allow the last one to be removed
        $this->putJson("/api/memberships/{$membership->id}",[
            'application' => null
        ])->assertStatus(422);
        $this->assertDatabaseHas('memberships', [
            'id' => $membership->id,
            'person_id' => $membership->person_id,
            'application' => $membership->application->toDateString(),
            'start' => null,
            'end' => null,
        ]);

        // Do allow delete a date if another one is added in the same request
        $newEndDate = Carbon::now();
        $this->putJson("/api/memberships/{$membership->id}",[
            'application' => null,
            'end' => $newEndDate->toDateString(),
        ])->assertStatus(200);
        $this->assertDatabaseHas('memberships', [
            'id' => $membership->id,
            'person_id' => $membership->person_id,
            'application' => null,
            'start' => null,
            'end' => $newEndDate->toDateString(),
        ]);



        // Change to other date
        $date_wrong_low  = Carbon::now()->subMonths(12)->toDateString();
        $date_before     = Carbon::now()->subMonths(10)->toDateString();
        $date_a          = Carbon::now()->subMonths(8)->toDateString();
        $date_b          = Carbon::now()->subMonths(6)->toDateString();
        $date_c          = Carbon::now()->subMonths(2)->toDateString();
        $date_after      = Carbon::now()->addMonths(2)->toDateString();
        $date_wrong_high = Carbon::now()->addMonths(4)->toDateString();


        // Keep the chronology with itself
        $this->putJson("/api/memberships/{$membership->id}",[
            'application' => $date_b,
            'start' => $date_a,
            'end' => $date_c
        ])->assertStatus(422);
        $this->putJson("/api/memberships/{$membership->id}",[
            'application' => $date_b,
            'start' => $date_c,
            'end' => $date_a
        ])->assertStatus(422);
        $this->putJson("/api/memberships/{$membership->id}",[
            'application' => $date_a,
            'start' => $date_c,
            'end' => $date_b
        ])->assertStatus(422);
        $this->putJson("/api/memberships/{$membership->id}", [
            'application' => $date_a,
            'start' => $date_b,
            'end' => $date_c
        ])->assertStatus(200);

        $this->assertDatabaseHas('memberships', [
            'id' => $membership->id,
            'person_id' => $membership->person_id,
            'application' => $date_a,
            'start' => $date_b,
            'end' => $date_c,
        ]);

        // Do not overlap a membership below
        $lowerMembership = $person->memberships()->create(['end' => $date_before]);
        $this->assertDatabaseHas('memberships', [
            'id' => $lowerMembership->id,
            'person_id' => $membership->person_id,
            'application' => null,
            'start' => null,
            'end' => $date_before
        ]);
        $this->putJson("/api/memberships/{$membership->id}", [
            'application' => $date_wrong_low
        ])->assertStatus(422);

        $lowerMembership->delete();

        $this->putJson("/api/memberships/{$membership->id}", [
            'application' => $date_wrong_low
        ])->assertStatus(200);

        $this->assertDatabaseHas('memberships', [
            'id' => $membership->id,
            'person_id' => $membership->person_id,
            'application' => $date_wrong_low,
            'start' => $date_b,
            'end' => $date_c,
        ]);

        // Do not overlap a membership above
        $upperMembership = $person->memberships()->create(['start' => $date_after]);
        $this->assertDatabaseHas('memberships', [
            'id' => $upperMembership->id,
            'person_id' => $membership->person_id,
            'application' => null,
            'start' => $date_after,
            'end' => null
        ]);
        $this->putJson("/api/memberships/{$membership->id}", [
            'end' => $date_wrong_high
        ])->assertStatus(422);
        $this->putJson("/api/memberships/{$membership->id}", [
            'end' => null
        ])->assertStatus(422);

        $upperMembership->delete();

        $this->putJson("/api/memberships/{$membership->id}", [
            'end' => null
        ])->assertStatus(200);

        $this->assertDatabaseHas('memberships', [
            'id' => $membership->id,
            'person_id' => $membership->person_id,
            'application' => $date_wrong_low,
            'start' => $date_b,
            'end' => null,
        ]);

    }

    /**
     * Test the basic usage of the destroy endpoint
     */
    public function testBasicDestroy()
    {
        $this->asAdmin();

        $this->delete("/api/memberships/1")->assertStatus(404);

        $membershipA = factory(Membership::class)->create();
        $membershipB = factory(Membership::class)->create();

        $this->assertDatabaseHas('memberships', ['id' => $membershipA->id]);
        $this->assertDatabaseHas('memberships', ['id' => $membershipB->id]);

        $this->delete("/api/memberships/{$membershipA->id}")->assertStatus(200);

        $this->assertDatabaseMissing('memberships', ['id' => $membershipA->id]);
        $this->assertDatabaseHas('memberships', ['id' => $membershipB->id]);

        $this->delete("/api/memberships/{$membershipA->id}")->assertStatus(404);
        $this->delete("/api/memberships/{$membershipB->id}")->assertStatus(200);

        $this->assertDatabaseMissing('memberships', ['id' => $membershipA->id]);
        $this->assertDatabaseMissing('memberships', ['id' => $membershipB->id]);

    }
}
