<?php

namespace Tests\Feature\Models;

use App\Permission;
use App\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RolesAndPermissionsTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Test the simplest Role creation
     *
     * @return void
     */
    public function testSimpleRole()
    {
        $attrs = ['name' => 'Simple Role', 'id' => 'simpleRole'];
        $record = [
            'name' => $attrs['name'],
            'id' => $attrs['id'],
            'description' => null,
            'is_required' => false,
            'is_visible' => false,
            'for_user' => false,
            'for_group' => false,
            'for_group_category' => false
        ];

        $role = Role::create($attrs);

        $this->assertInstanceOf(Role::class, $role);
        $this->assertEquals($attrs['id'], $role->id);
        $this->assertEquals($attrs['name'], $role->name);

        $this->assertDatabaseHas('roles', $record);

        $collectedRole = Role::find($role->id);

        $this->assertInstanceOf(Role::class, $collectedRole);
        $this->assertEquals($attrs['name'], $collectedRole->name);

        $collectedRole->delete();

        $this->assertDatabaseMissing('roles', $record);
    }

    /**
     * Tests the creation of a tree structure for a role
     *
     * @return void
     */
    public function testRoleTree()
    {
        $parent = Role::create(['id' => 'parentRole']);
        $child = Role::create(['id' => 'childRole']);
        $subChild = Role::create(['id' => 'subChildRole']);

        $this->assertDatabaseHas('roles', ['id' => 'parentRole']);
        $this->assertDatabaseHas('roles', ['id' => 'childRole']);
        $this->assertDatabaseHas('roles', ['id' => 'subChildRole']);

        // Assign the child to the parent;
        $parent->assign($child);

        $this->assertDatabaseHas('role_role', [
            'parent_id' => $parent->id,
            'child_id' => $child->id
        ]);

        // Assign the sub-child to the parent.
        $subChild->assignTo($child);

        $this->assertDatabaseHas('role_role', [
            'parent_id' => $child->id,
            'child_id' => $subChild->id,
        ]);

        // Check the state of the tree.
        $this->assertCount(0, $parent->parentRoles);
        $this->assertCount(1, $parent->childRoles);
        $this->assertCount(1, $child->parentRoles);
        $this->assertCount(1, $child->childRoles);
        $this->assertCount(1, $subChild->parentRoles);
        $this->assertCount(0, $subChild->childRoles);
    }

    /**
     * @return void
     */
    public function testRoleHasPermission() {
        $role = Role::create(['id' => 'testRoleHasPersmissionRole']);
        $permission1 = Permission::create(['id' => 'testRoleHasPermissionPermission1']);
        $permission2 = Permission::create(['id' => 'testRoleHasPermissionPermission2']);

        $role->assign($permission1);

        $this->assertDatabaseHas('role_permission', [
            'role_id' => $role->id,
            'permission_id' => $permission1->id
        ]);

        $permission2->assignTo($role);

        $this->assertDatabaseHas('role_permission',[
            'role_id' => $role->id,
            'permission_id' => $permission2->id,
        ]);

        $this->assertCount(2, $role->childPermissions);
        $this->assertCount(1, $permission1->parentRoles);
        $this->assertCount(1, $permission2->parentRoles);

    }

    /**
     * @return void
     */
    public function testRoleMultipleAssignments() {
        $role1 = Role::create(['id' => 'testRoleMultipleAssignments:Role1']);
        $role2 = Role::create(['id' => 'testRoleMultipleAssignments:Role2']);

        $permission1 = Permission::create(['id' => 'testRoleMultipleAssignments:Permission1']);
        $permission2 = Permission::create(['id' => 'testRoleMultipleAssignments:Permission2']);

        $role1->assign($permission1);
        $role1->assign($permission2);

        $role2->assign($permission1, $permission2);

        $this->assertDatabaseHas('role_permission', [
            'role_id' => $role1->id,
            'permission_id' => $permission1->id,
        ]);

        $this->assertDatabaseHas('role_permission', [
            'role_id' => $role1->id,
            'permission_id' => $permission2->id,
        ]);

        $this->assertDatabaseHas('role_permission', [
            'role_id' => $role2->id,
            'permission_id' => $permission1->id,
        ]);

        $this->assertDatabaseHas('role_permission', [
            'role_id' => $role2->id,
            'permission_id' => $permission2->id,
        ]);
    }

    /**
     * Test the tree structure of the Permissions
     *
     * @return void
     */
    public function testPermissionTree() {
        $parent = Permission::create(['id' => 'parentPerm']);
        $child = Permission::create(['id' => 'childPerm']);
        $subChild = Permission::create(['id' => 'subChildPerm']);

        $parent->assign($child);

        $this->assertDatabaseHas('permission_permission', [
            'parent_id' => $parent->id,
            'child_id' => $child->id
        ]);

        $subChild->assignTo($child);

        $this->assertDatabaseHas('permission_permission', [
            'parent_id' => $child->id,
            'child_id' => $subChild->id
        ]);

        $this->assertCount(0, $parent->parentPermissions);
        $this->assertCount(1, $parent->childPermissions);
        $this->assertCount(1, $child->parentPermissions);
        $this->assertCount(1, $child->childPermissions);
        $this->assertCount(1, $subChild->parentPermissions);
        $this->assertCount(0, $subChild->childPermissions);
    }
}
