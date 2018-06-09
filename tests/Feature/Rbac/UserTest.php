<?php

namespace Tests\Feature\Rbac;

use App\Group;
use App\Role;
use App\Services\Rbac\RbacPostgres;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $user = User::query()->where(['email' => 'ik@roelweb.com'])->first();


        $service = new RbacPostgres;

        var_dump($service->permissionGetAllPermissions(['model.persons.maintain|owned'], ['owned']));

        $this->assertTrue(true);
    }
}
