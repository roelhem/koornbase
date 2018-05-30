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

        $response = $this->post('/api/persons', [
            'name' => 'Roel Hemerik',
            'name_short' => 'Roel',
            'name_formal' => 'R.A.B. Hemerik',
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
            'name' => 'Roel Hemerik',
            'name_short' => 'Roel',
            'name_formal' => 'R.A.B. Hemerik',
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
