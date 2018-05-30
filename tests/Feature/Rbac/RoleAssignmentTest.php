<?php

namespace Tests\Feature;

use App\Group;
use App\GroupCategory;
use App\Permission;
use App\Role;
use App\Traits\HasAssignedRoles;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleAssignmentTest extends TestCase
{

    use RefreshDatabase;

    public function assignableProvider() {

        return [
            'USER' => [User::class],
            'GROUP' => [Group::class],
            'GROUP_CATEGORY' => [GroupCategory::class],
        ];
    }

    /**
     * Test if the model uses the \App\Traits\HasAssignedRoles trait.
     *
     * @param $modelClass
     * @dataProvider assignableProvider
     * @return void
     */
    public function testAssignableModelTrait($modelClass) {
        $model = factory($modelClass)->create();
        $this->assertContains(HasAssignedRoles::class, class_uses($model));
    }

    /**
     * Tests if a role can be assigned to a user.
     *
     * @param string $modelClass
     * @dataProvider assignableProvider
     * @return void
     */
    public function testAssignableModelRoles($modelClass)
    {
        $model = factory($modelClass)->create();

        $role = factory(Role::class)->create();
        $childRole = factory(Role::class)->create();
        $otherRole = factory(Role::class)->create();
        $parentRole = factory(Role::class)->create();

        $role->assignTo($model);
        $role->assign($childRole);

        $model->assignRole($otherRole);

        $parentRole->assign($role, $otherRole);

        $this->assertTrue($model->hasRole($role));
        $this->assertTrue($model->hasRole($childRole));
        $this->assertTrue($model->hasRole($otherRole));
        $this->assertFalse($model->hasRole($parentRole));
    }

    /**
     * Test if a model with assigned roles has the right permissions
     *
     * @param $modelClass
     * @dataProvider assignableProvider
     */
    public function testAssignableModelPermissions($modelClass) {
        $modelA = factory($modelClass)->create();
        $modelB = factory($modelClass)->create();

        $role = factory(Role::class)->create();
        $childRole = factory(Role::class)->create();

        $permissionA = factory(Permission::class)->create();
        $permissionB = factory(Permission::class)->create();
        $childPermission = factory(Permission::class)->create();
        $unassignedPermission = factory(Permission::class)->create();

        $role->assign($permissionA, $childRole);
        $childRole->assign($permissionB);
        $permissionA->assign($childPermission);

        $modelA->assignRole($role);

        $this->assertTrue($modelA->hasPermission($permissionA));
        $this->assertTrue($modelA->hasPermission($permissionB));
        $this->assertTrue($modelA->hasPermission($childPermission));
        $this->assertFalse($modelA->hasPermission($unassignedPermission));

        $modelB->assignRole($childRole);

        $this->assertFalse($modelB->hasPermission($permissionA));
        $this->assertTrue($modelB->hasPermission($permissionB));
        $this->assertFalse($modelB->hasPermission($childPermission));
        $this->assertFalse($modelB->hasPermission($unassignedPermission));
    }
}
