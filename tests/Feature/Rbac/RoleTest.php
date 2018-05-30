<?php

namespace Tests\Feature\Rbac;

use App\Permission;
use App\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{

    use RefreshDatabase;


    /**
     * Test the RBAC structure with just 1 in role depth.
     *
     * @return void
     */
    public function testDepthOne() {
        $parent = factory(Role::class)->create();
        $childA = factory(Role::class)->create();
        $childB = factory(Role::class)->create();
        $notChild = factory(Role::class)->create();

        $this->assertRoleExists($parent->id);
        $this->assertRoleExists($childA->id);
        $this->assertRoleExists($childB->id);
        $this->assertRoleExists($notChild->id);

        $parent->assign($childA, $childB);

        $this->assertHasRole($parent, $childA);
        $this->assertHasRole($parent, $childB);
        $this->assertHasNoRole($parent, $notChild);

        $permA = factory(Permission::class)->create();
        $permB = factory(Permission::class)->create();

        $parent->assign($permA);
        $childB->assign($permB);

        $this->assertHasPerm($parent, $permA);
        $this->assertHasPerm($parent, $permB);
    }

    /**
     * Test the RBAC structure with just 2 in role depth.
     */
    public function testDepthTwo() {
        $parent = factory(Role::class)->create();
        $child = factory(Role::class)->create();
        $subChild = factory(Role::class)->create();

        $parent->assign($child);
        $child->assign($subChild);

        $this->assertHasRole($parent, $child);
        $this->assertHasRole($parent, $subChild);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- HELPER ASSERTION FUNCTIONS ------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    /**
     * Checks if the given model has the given role.
     *
     * @param $model
     * @param $role
     */
    public function assertHasRole($model, $role, $message = null) {
        if($message === null) {
            $message = "Asserting a model has the role: $role.";
        }

        return $this->assertTrue($model->hasRole($role), $message);
    }

    /**
     * Checks if the given model does not have the given role
     *
     * @param $model
     * @param $role
     */
    public function assertHasNoRole($model, $role, $message = null) {
        if($message === null) {
            $message = "Asserting a model does not have the role: $role.";
        }

        return $this->assertFalse($model->hasRole($role), $message);
    }

    /**
     * Checks if the given model has the given permission.
     *
     * @param $model
     * @param $permission
     */
    public function assertHasPerm($model, $permission, $message = null) {
        if($message === null) {
            $message = "Asserting a model has the permission: $permission.";
        }

        return $this->assertTrue($model->hasPermission($permission), $message);
    }

    /**
     * Checks if the given model does not have the given permission
     *
     * @param $model
     * @param $permission
     */
    public function assertHasNoPerm($model, $permission, $message = null) {
        if($message === null) {
            $message = "Asserting a model does not have the permission: $permission.";
        }

        return $this->assertFalse($model->hasPermission($permission), $message);
    }

    /**
     * Checks if a role with the specified id exists in the database.
     *
     * @param $role_id
     * @return $this
     */
    public function assertRoleExists($role_id) {
        return $this->assertDatabaseHas('roles', ['id' => $role_id]);
    }

    /**
     * Checks if a permission with the specified id exists in the database.
     *
     * @param $permission_id
     * @return $this
     */
    public function assertPermissionExists($permission_id) {
        return $this->assertDatabaseHas('permissions', ['id' => $permission_id]);
    }
}
