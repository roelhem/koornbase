<?php

namespace Tests\Feature\Rbac;

use App\Interfaces\Rbac\RbacAuthorizable;
use App\Interfaces\Rbac\RbacPermissionAuthorizable;
use App\Permission;
use App\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionTest extends TestCase
{

    use RefreshDatabase;


    /**
     * Test the RBAC structure with just 1 in the permissions.
     *
     * @return void
     */
    public function testDepthOne() {
        $role = factory(Role::class)->create();
        $permA = factory(Permission::class)->create();
        $permB = factory(Permission::class)->create();
        $permC = factory(Permission::class)->create();

        $this->assertRoleExists($role->id);
        $this->assertPermissionExists($permA->id);
        $this->assertPermissionExists($permB->id);
        $this->assertPermissionExists($permC->id);

        $role->assign($permA);
        $permB->assignTo($role);

        $this->assertHasPerm($role, $permA->id);
        $this->assertHasPerm($role, $permB->id);
        $this->assertHasNoPerm($role, $permC->id);
    }

    /**
     * Test the RBAC structure with a depth of just 2 in the permissions tree.
     *
     * @return void
     */
    public function testDepthTwo() {
        $roleA = factory(Role::class)->create();
        $roleB = factory(Role::class)->create();
        $permA = factory(Permission::class)->create();
        $permAChild = factory(Permission::class)->create();
        $permB = factory(Permission::class)->create();
        $permBChild = factory(Permission::class)->create();
        $permC = factory(Permission::class)->create();
        $permCChild = factory(Permission::class)->create();
        $permParent = factory(Permission::class)->create();

        $permA->assign($permAChild);
        $permB->assign($permBChild);
        $permC->assign($permCChild);

        $permParent->assign($permA, $permB);

        $roleA->assign($permA, $permCChild);
        $roleB->assign($permC);

        $this->assertDatabaseHas('role_permission', [
            'role_id' => $roleA->id,
            'permission_id' => $permCChild->id,
        ]);

        $this->assertHasPerm($roleA, $permA->id);
        $this->assertHasPerm($roleA, $permAChild->id);
        $this->assertHasNoPerm($roleA, $permB->id);
        $this->assertHasNoPerm($roleA, $permBChild->id);
        $this->assertHasNoPerm($roleA, $permC->id);
        $this->assertHasPerm($roleA, $permCChild->id, "roleA, permission: $permCChild");
        $this->assertHasNoPerm($roleA, $permParent->id);

        $this->assertHasNoPerm($roleB, $permA->id);
        $this->assertHasNoPerm($roleB, $permAChild->id);
        $this->assertHasNoPerm($roleB, $permB->id);
        $this->assertHasNoPerm($roleB, $permBChild->id);
        $this->assertHasPerm($roleB, $permC->id);
        $this->assertHasPerm($roleB, $permCChild->id, "roleB, permission: $permCChild");
        $this->assertHasNoPerm($roleB, $permParent->id);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- HELPER ASSERTION FUNCTIONS ------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Checks if the given model has the given permission.
     *
     * @param RbacPermissionAuthorizable $model
     * @param $permission
     * @throws
     */
    public function assertHasPerm($model, $permission, $message = null) {
        if($message === null) {
            $message = "Asserting a model has the permission: $permission.";
        }

        $this->assertTrue($model->hasPermission($permission), $message);
    }

    /**
     * Checks if the given model does not have the given permission
     *
     * @param RbacPermissionAuthorizable $model
     * @param $permission
     * @throws
     */
    public function assertHasNoPerm($model, $permission, $message = null) {
        if($message === null) {
            $message = "Asserting a model does not have the permission: $permission.";
        }

        $this->assertFalse($model->hasPermission($permission), $message);
    }

    /**
     * Checks if a role with the specified id exists in the database.
     *
     * @param $role_id
     */
    public function assertRoleExists($role_id) {
        $this->assertDatabaseHas('roles', ['id' => $role_id]);
    }

    /**
     * Checks if a permission with the specified id exists in the database.
     *
     * @param $permission_id
     */
    public function assertPermissionExists($permission_id) {
        $this->assertDatabaseHas('permissions', ['id' => $permission_id]);
    }
}
