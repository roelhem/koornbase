<?php

namespace Tests\Feature\Api;

use App\Person;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Tests the basic index endpoint usage.
     */
    public function testBasicIndex()
    {
        $this->asSuper();

        factory(User::class, 4)->create();

        $users = User::query()->get();

        $this->getJson("/api/users")
            ->assertStatus(200)
            ->assertJsonCount(count($users), 'data')
            ->assertJson([
                'data' => $users->map(function($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email
                    ];
                })->all()
            ]);
    }

    /**
     * Test the basic usage of the store endpoint.
     */
    public function testBasicStore()
    {
        $this->asSuper();

        // Empty request
        $this->postJson("/api/users",[])->assertStatus(422);

        // Minimal request
        $this->postJson("/api/users",[
            'email'    => 'test@koornbeurs.nl',
            'name'     => 'test-user',
            'password' => 'eenWachtwoordMetMeerDan8Tekens!'
        ])->assertStatus(201);
        $this->assertDatabaseHas('users',[
            'email'    => 'test@koornbeurs.nl',
            'name'     => 'test-user'
        ]);

        // Unique email and name
        $this->postJson("/api/users",[
            'email'    => 'test2@koornbeurs.nl',
            'name'     => 'test-user',
            'password' => 'eenWachtwoordMetMeerDan8Tekens!'
        ])->assertStatus(422);
        $this->postJson("/api/users",[
            'email'    => 'test@koornbeurs.nl',
            'name'     => 'test2-user',
            'password' => 'eenWachtwoordMetMeerDan8Tekens!'
        ])->assertStatus(422);

        // Requirement of email, name and password
        $this->postJson("/api/users",[
            'name'     => 'test2-user',
            'password' => 'eenWachtwoordMetMeerDan8Tekens!'
        ])->assertStatus(422);
        $this->postJson("/api/users",[
            'email'    => 'test2@koornbeurs.nl',
            'password' => 'eenWachtwoordMetMeerDan8Tekens!'
        ])->assertStatus(422);
        $this->postJson("/api/users",[
            'email'    => 'test2@koornbeurs.nl',
            'name'     => 'test2-user'
        ])->assertStatus(422);

        // Including a person.
        $this->postJson("/api/users",[
            'email'    => 'without-person@koornbeurs.nl',
            'name'     => 'user-without-person',
            'password' => 'eenWachtwoordMetMeerDan8Tekens!',
            'person'   => null
        ])->assertStatus(201);
        $this->assertDatabaseHas('users',[
            'email'     => 'without-person@koornbeurs.nl',
            'name'      => 'user-without-person',
            'person_id' => null
        ]);

        $person = factory(Person::class)->create();

        $this->postJson("/api/users",[
            'email'    => 'with-person@koornbeurs.nl',
            'name'     => 'user-with-person',
            'password' => 'eenWachtwoordMetMeerDan8Tekens!',
            'person'   => $person->id
        ])->assertStatus(201);
        $this->assertDatabaseHas('users',[
            'email'     => 'with-person@koornbeurs.nl',
            'name'      => 'user-with-person',
            'person_id' => $person->id
        ]);

    }

    /**
     * Tests the basic usage of the show endpoint
     */
    public function testBasicShow()
    {
        $this->asSuper();

        $this->getJson("/api/users/0")->assertStatus(404);
        $this->getJson("/api/users/bla")->assertStatus(404);
        $this->getJson("/api/users/test@hoi.com")->assertStatus(404);

        $user = factory(User::class)->create();

        $statuses = [
            $this->getJson("/api/users/{$user->id}"),
            $this->getJson("/api/users/{$user->name}"),
            $this->getJson("/api/users/{$user->email}")
        ];

        foreach ($statuses as $status) {
            $status->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'id' => $user->id,
                        'email' => $user->email,
                        'name' => $user->name
                    ]
                ]);
        }

    }

    /**
     * Tests the basic usage of the update endpoint
     */
    public function testBasicUpdate()
    {
        $this->asSuper();

        $this->putJson("/api/users/0", [])->assertStatus(404);
        $this->putJson("/api/users/bla", [])->assertStatus(404);
        $this->putJson("/api/users/test@hoi.com", [])->assertStatus(404);

        $user = factory(User::class)->create();

        // Test empty endpoint
        $this->putJson("/api/users/{$user->id}", [])->assertStatus(200);



        // Test change each
        $this->putJson("/api/users/{$user->name}", [
            'name' => 'updated-user'
        ])->assertStatus(200);
        $this->assertDatabaseHas('users',[
            'id' => $user->id,
            'name' => 'updated-user',
            'email' => $user->email,
        ]);
        $this->putJson("/api/users/{$user->id}", [
            'name' => 'updated-user'
        ])->assertStatus(200);

        $this->putJson("/api/users/{$user->id}", [
            'email' => 'updated-email'
        ])->assertStatus(422);
        $this->putJson("/api/users/{$user->id}", [
            'email' => 'updated-email@koornbeurs.nl'
        ])->assertStatus(200);
        $this->assertDatabaseHas('users',[
            'id' => $user->id,
            'name' => 'updated-user',
            'email' => 'updated-email@koornbeurs.nl',
        ]);
        $this->putJson("/api/users/{$user->id}", [
            'email' => 'updated-email@koornbeurs.nl'
        ])->assertStatus(200);

        $personA = factory(Person::class)->create();
        $this->putJson("/api/users/{$user->id}", [
            'person' => $personA->id,
        ])->assertStatus(200);
        $this->assertDatabaseHas('users',[
            'id' => $user->id,
            'name' => 'updated-user',
            'email' => 'updated-email@koornbeurs.nl',
            'person_id' => $personA->id,
        ]);

        $this->putJson("/api/users/{$user->id}", [
            'person' => null,
        ])->assertStatus(200);
        $this->assertDatabaseHas('users',[
            'id' => $user->id,
            'name' => 'updated-user',
            'email' => 'updated-email@koornbeurs.nl',
            'person_id' => null,
        ]);


        // Test update all
        $personB = factory(Person::class)->create();
        $this->putJson("/api/users/{$user->id}", [
            'name' => 'updated-user-name2',
            'email' => 'updated-email-name2@koornbeurs.nl',
            'person' => strval($personB->id),
        ])->assertStatus(200);
        $this->assertDatabaseHas('users',[
            'id' => $user->id,
            'name' => 'updated-user-name2',
            'email' => 'updated-email-name2@koornbeurs.nl',
            'person_id' => $personB->id,
        ]);


        // Test no duplicates
        $user2 = factory(User::class)->create();
        $this->putJson("/api/users/{$user2->id}", [
            'name' => 'updated-user-name2',
        ])->assertStatus(422);
        $this->putJson("/api/users/{$user2->id}", [
            'email' => 'updated-email-name2@koornbeurs.nl',
        ])->assertStatus(422);
    }
}
