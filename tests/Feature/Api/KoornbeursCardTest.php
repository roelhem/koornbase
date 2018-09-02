<?php

namespace Tests\Feature\Api;

use App\KoornbeursCard;
use App\Person;
use App\User;
use Carbon\Carbon;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KoornbeursCardTest extends TestCase
{

    use RefreshDatabase, UsePassportAsAdmin;

    /**
     * Tests the basic usage of the index endpoint
     *
     * @return void
     */
    public function testBasicIndex()
    {
        $this->asAdmin();

        $this->get("/api/koornbeurs-cards")->assertStatus(200)
            ->assertJsonCount(0, 'data');

        $cardA = factory(KoornbeursCard::class)->create();
        $cardB = factory(KoornbeursCard::class)->create();

        $this->assertInstanceOf(KoornbeursCard::class, $cardA);
        $this->assertInstanceOf(KoornbeursCard::class, $cardB);

        $this->get("/api/koornbeurs-cards")->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJson([
                'data' => [
                    ['id' => $cardA->id, 'ref' => $cardA->ref, 'version' => $cardA->version],
                    ['id' => $cardB->id, 'ref' => $cardB->ref, 'version' => $cardB->version]
                ]
            ]);
    }

    /**
     * Test the basic usage of the show endpoint
     */
    public function testBasicShow()
    {
        $this->asAdmin();

        $this->get("/api/koornbeurs-cards/1")->assertStatus(404);
        $this->get("/api/koornbeurs-cards/v_n")->assertStatus(404);
        $this->get("/api/koornbeurs-cards/_n")->assertStatus(404);
        $this->get("/api/koornbeurs-cards/hallo")->assertStatus(404);

        $card = factory(KoornbeursCard::class)->create();

        $results = [
            $this->get("/api/koornbeurs-cards/{$card->id}"),
            $this->get("/api/koornbeurs-cards/{$card->version}_{$card->ref}"),
            $this->get("/api/koornbeurs-cards/_{$card->ref}")
        ];

        foreach ($results as $name => $result) {
            $result->assertStatus(200);
            $result->assertJson([
                'data' => [
                    'id' => $card->id,
                    'version' => $card->version,
                    'ref' => $card->ref
                ]
            ]);
        }
    }

    /**
     * Test the basic usage of the store endpoint
     */
    public function testBasicStore()
    {
        $user = $this->asAdmin();

        // TEST INVALID INPUT
        $card = factory(KoornbeursCard::class)->make();

        $this->postJson("/api/koornbeurs-cards", [])->assertStatus(422);
        $this->postJson("/api/koornbeurs-cards", ['ref' => $card->ref])->assertStatus(422);
        $this->postJson("/api/koornbeurs-cards", ['version' => $card->version])->assertStatus(422);
        $this->postJson("/api/koornbeurs-cards", [
            'ref' => $card->ref,
            'version' => $card->version,
            'person' => "DEZE KAN NIET"
        ])->assertStatus(422);
        $this->postJson("/api/koornbeurs-cards", [
            'ref' => $card->ref,
            'version' => $card->version,
            'person' => -1
        ])->assertStatus(422);
        $this->postJson("/api/koornbeurs-cards", [
            'ref' => $card->ref,
            'version' => $card->version,
            'activated_at' => 'BLA'
        ])->assertStatus(422);
        $this->postJson("/api/koornbeurs-cards", [
            'ref' => $card->ref,
            'version' => $card->version,
            'deactivated_at' => 'BLA'
        ])->assertStatus(422);

        // TEST CARD WITHOUT OWNER
        $cardWithoutOwner = factory(KoornbeursCard::class)->make();
        $this->postJson("/api/koornbeurs-cards", [
            'ref' => $cardWithoutOwner->ref,
            'version' => $cardWithoutOwner->version,
            'activated_at' => $cardWithoutOwner->activated_at->toDateTimeString(),
            'deactivated_at' => $cardWithoutOwner->deactivated_at->toDateTimeString(),
            'remarks' => $cardWithoutOwner->remarks,
        ])->assertStatus(201);

        $this->assertDatabaseHas('koornbeurs_cards', [
            'owner_id' => null,
            'ref' => $cardWithoutOwner->ref,
            'version' => $cardWithoutOwner->version,
            'activated_at' => $cardWithoutOwner->activated_at->toDateTimeString(),
            'deactivated_at' => $cardWithoutOwner->deactivated_at->toDateTimeString(),
            'remarks' => $cardWithoutOwner->remarks,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        // CHECK NO DOUBLE
        $this->postJson("/api/koornbeurs-cards", [
            'ref' => $cardWithoutOwner->ref,
            'version' => $cardWithoutOwner->version
        ])->assertStatus(422);

        // TEST CARD WITH OWNER
        $owner = factory(Person::class)->create();
        $cardWithOwner = factory(KoornbeursCard::class)->make([
            'owner_id' => $owner->id
        ]);
        $this->postJson("/api/koornbeurs-cards", [
            'person' => $owner->id,
            'ref' => $cardWithOwner->ref,
            'version' => $cardWithOwner->version,
        ])->assertStatus(201);

        $this->assertDatabaseHas('koornbeurs_cards', [
            'owner_id' => $owner->id,
            'ref' => $cardWithOwner->ref,
            'version' => $cardWithOwner->version,
            'activated_at' => null,
            'deactivated_at' => null,
            'remarks' => null,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);
    }

    /**
     * Test the basic usage of the update endpoint
     */
    public function testBasicUpdate() {

        $user = $this->asAdmin();

        $this->putJson("/api/koornbeurs-cards/_test")->assertStatus(404);
        $this->patchJson("/api/koornbeurs-cards/2")->assertStatus(404);

        $card = factory(KoornbeursCard::class)->states('no-owner')->create();
        $this->assertNull($card->owner_id);


        // EMPTY UPDATE
        $this->putJson("/api/koornbeurs-cards/{$card->id}", [])
            ->assertStatus(200);

        $this->assertDatabaseHas('koornbeurs_cards', [
            'id' => $card->id,
            'ref' => $card->ref,
            'version' => $card->version,
            'owner_id' => $card->owner_id,
            'activated_at' => $card->activated_at->toDateTimeString(),
            'deactivated_at' => $card->deactivated_at->toDateTimeString(),
            'remarks' => $card->remarks,
            'updated_by' => $user->id,
        ]);

        // CHANGE REMARKS AND DEACTIVATED_AT
        $this->putJson("/api/koornbeurs-cards/{$card->version}_{$card->ref}", [
            'remarks' => 'New Remarks',
            'deactivated_at' => null
        ])->assertStatus(200);

        $this->assertDatabaseHas('koornbeurs_cards', [
            'id' => $card->id,
            'ref' => $card->ref,
            'version' => $card->version,
            'owner_id' => $card->owner_id,
            'activated_at' => $card->activated_at->toDateTimeString(),
            'deactivated_at' => null,
            'remarks' => 'New Remarks',
            'updated_by' => $user->id,
        ]);

        // CHANGE AND REPEAT REF
        $this->putJson("/api/koornbeurs-cards/{$card->id}", [
            'ref' => $card->ref
        ])->assertStatus(200);

        $this->assertDatabaseHas('koornbeurs_cards', [
            'id' => $card->id,
            'ref' => $card->ref
        ]);

        // CHANGE ALL VARIABLES
        $owner = factory(Person::class)->create();
        $newCardValues = factory(KoornbeursCard::class)->make(['owner_id' => $owner->id]);
        $this->putJson("/api/koornbeurs-cards/{$card->version}_{$card->ref}", [
            'person' => $owner->id,
            'activated_at' => $newCardValues->activated_at->toDateTimeString(),
            'deactivated_at' => $newCardValues->deactivated_at->toDateTimeString(),
            'remarks' => $newCardValues->remarks
        ])->assertStatus(200);

        $this->assertDatabaseHas('koornbeurs_cards', [
            'id' => $card->id,
            'owner_id' => $newCardValues->owner_id,
            'activated_at' => $newCardValues->activated_at->toDateTimeString(),
            'deactivated_at' => $newCardValues->deactivated_at->toDateTimeString(),
            'remarks' => $newCardValues->remarks,
            'updated_by' => $user->id,
        ]);

        // WRONG VARIABLE VALUES

        $this->putJson("/api/koornbeurs-cards/{$card->id}", [
            'person' => -1
        ])->assertStatus(422);

        $this->putJson("/api/koornbeurs-cards/{$card->id}", [
            'activated_at' => "BLA"
        ])->assertStatus(422);

        $this->putJson("/api/koornbeurs-cards/{$card->id}", [
            'activated_at' => "BLU"
        ])->assertStatus(422);

    }

    /**
     * Test the basic usage of the delete endpoint
     */
    public function testBasicDelete() {

        $this->asAdmin();

        $this->delete("/api/koornbeurs-cards/1")->assertStatus(404);
        $this->delete("/api/koornbeurs-cards/_test")->assertStatus(404);

        $card = factory(KoornbeursCard::class)->create();


        $this->assertDatabaseHas('koornbeurs_cards', ['id' => $card->id]);
        $this->delete("/api/koornbeurs-cards/{$card->id}")->assertStatus(200);
        $this->assertDatabaseMissing('koornbeurs_cards', ['id' => $card->id]);

    }

}
