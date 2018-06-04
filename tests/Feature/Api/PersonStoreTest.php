<?php

namespace Tests\Feature\Api;

use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PersonStoreTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testApiCall()
    {
        Passport::actingAs(factory(User::class)->create());

        $response = $this->postJson('/api/persons', [
            'name_first' => 'Roel',
            'name_last' => 'Hemerik',
            'name_initials' => 'rab',
            'birth_date' => '1993-09-20',
            'remarks' => 'Dit ben ik zelf!',

            'emailAddresses' => [
                [
                    'label' => 'privé',
                    'email_address' => 'ik@roelweb.com',
                    'options' => [
                        'is_primary' => true
                    ]
                ],
                [
                    'label' => 'koornbeurs',
                    'email_address' => 'koornbeurs@roelweb.com',
                ]
            ]
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('persons', [
            'name_first' => 'Roel',
            'name_last' => 'Hemerik',
            'name_initials' => 'R.A.B.',
            'birth_date' => '1993-09-20',
            'remarks' => 'Dit ben ik zelf!',
        ]);

        $this->assertDatabaseHas('person_email_addresses', [
            'label' => 'privé',
            'email_address' => 'ik@roelweb.com',
            'options' => '{"is_primary":true}',
        ]);

        $this->assertDatabaseHas('person_email_addresses', [
            'label' => 'koornbeurs',
            'email_address' => 'koornbeurs@roelweb.com',
            'options' => "{}",
        ]);
    }
}
