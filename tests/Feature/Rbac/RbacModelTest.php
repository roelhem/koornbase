<?php

namespace Tests\Feature\Rbac;

use App\Services\Rbac\RbacGenerator;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RbacModelTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        resolve(RbacGenerator::class)->run();

        $this->assertDatabaseHas('roles', ['id' => 'person']);

        $user = User::findOrFail(2);

        $this->assertTrue($user->hasRole('person'));

        foreach($user->getRoles() as $role) {
            echo $role->getId().':   '.$role->getName().PHP_EOL;
        }
    }
}
