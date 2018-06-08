<?php

namespace Tests\Feature\Rbac;

use App\Group;
use App\GroupCategory;
use App\Permission;
use App\Person;
use App\Role;
use App\Traits\HasAssignedRoles;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelRoleInheritanceTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Tests if a group can inherit the roles of its group-category.
     *
     * @return void
     */
    public function testGroup()
    {
        $categoryFactory = factory(GroupCategory::class);
        $groupFactory = factory(Group::class);
        $roleFactory = factory(Role::class);
        $permissionFactory = factory(Permission::class);

        $category = $categoryFactory->create();
        $unusedCategory = $categoryFactory->create();
        $group = $groupFactory->create(['category_id' => $category->id]);

        $role = $roleFactory->create();
        $childRole = $roleFactory->create();
        $unusedRole = $roleFactory->create();

        $permissionA = $permissionFactory->create();
        $permissionB = $permissionFactory->create();
        $unusedPermission = $permissionFactory->create();

        $role->assignAll([$childRole, $permissionA]);
        $childRole->assign($permissionB);
        $unusedRole->assign($unusedPermission);

        $category->assignRole($role);
        $unusedCategory->assignRole($unusedRole);

        // CHECK THE ROLES
        // For the category
        $this->assertTrue($category->hasRole($role));
        $this->assertTrue($category->hasRole($childRole));
        $this->assertFalse($category->hasRole($unusedRole));
        // For the unused category
        $this->assertFalse($unusedCategory->hasRole($role));
        $this->assertFalse($unusedCategory->hasRole($childRole));
        $this->assertTrue($unusedCategory->hasRole($unusedRole));
        // For the group
        $this->assertTrue($group->hasRole($role));
        $this->assertTrue($group->hasRole($childRole));
        $this->assertFalse($group->hasRole($unusedRole));

        // CHECK THE PERMISSIONS
        // For the category
        $this->assertTrue($category->hasPermission($permissionA));
        $this->assertTrue($category->hasPermission($permissionB));
        $this->assertFalse($category->hasPermission($unusedPermission));
        // For the unused category
        $this->assertFalse($unusedCategory->hasPermission($permissionA));
        $this->assertFalse($unusedCategory->hasPermission($permissionB));
        $this->assertTrue($unusedCategory->hasPermission($unusedPermission));
        // For the group
        $this->assertTrue($group->hasPermission($permissionA));
        $this->assertTrue($group->hasPermission($permissionB));
        $this->assertFalse($group->hasPermission($unusedPermission));

    }

    /**
     * Tests if a user can inherit the roles of its groups and the groupCategories of those groups.
     *
     * @return void
     */
    public function testUser() {

        // CREATING THE NEEDED FACTORIES
        $categoryFactory = factory(GroupCategory::class);
        $groupFactory = factory(Group::class);
        $personFactory = factory(Person::class);
        $userFactory = factory(User::class);

        $roleFactory = factory(Role::class);
        $permissionFactory = factory(Permission::class);

        // SETUP FOR THE GROUP_CATEGORY->GROUP->PERSON->USER STRUCTURE
        $categoryA = $categoryFactory->create();
        $groupA = $groupFactory->create(['category_id' => $categoryA->id]);
        $groupB = $groupFactory->create();
        $unusedGroup = $groupFactory->create();

        $person = $personFactory->create();
        $person->groups()->attach([$groupA->id, $groupB->id]);

        $user = $userFactory->create();
        $user->person()->associate($person);

        // SETUP FOR THE GROUP->ROLES->PERMISSIONS STRUCTURE
        $roleForUser = $roleFactory->create();
        $permissionForUser = $permissionFactory->create();
        $roleForUser->assign($permissionForUser)->assignTo($user);

        $roleForCategory = $roleFactory->create();
        $permissionForCategory = $permissionFactory->create();
        $roleForCategory->assign($permissionForCategory)->assignTo($categoryA);

        $roleA = $roleFactory->create();
        $permissionA = $permissionFactory->create();
        $roleA->assign($permissionA)->assignTo($groupA);

        $roleB = $roleFactory->create();
        $permissionB = $permissionFactory->create();
        $roleB->assign($permissionB)->assignTo($groupB);

        $unusedRole = $roleFactory->create();
        $unusedPermission = $permissionFactory->create();
        $unusedRole->assign($unusedPermission)->assignTo($unusedGroup);

        // ASSERTIONS FOR THE ROLES AND PERMISSIONS
        // For categoryA
        $this->assertFalse($categoryA->hasRole($roleForUser), 'CategoryA has RoleForUser');
        $this->assertFalse($categoryA->hasPermission($permissionForUser), 'CategoryA has PermissionForUser');
        $this->assertTrue($categoryA->hasRole($roleForCategory), 'CategoryA has RoleForCategory');
        $this->assertTrue($categoryA->hasPermission($permissionForCategory), 'CategoryA has PermissionForCategory');
        $this->assertFalse($categoryA->hasRole($roleA), 'CategoryA has RoleA');
        $this->assertFalse($categoryA->hasPermission($permissionA));
        $this->assertFalse($categoryA->hasRole($roleB), 'CategoryA has RoleB');
        $this->assertFalse($categoryA->hasPermission($permissionB));
        $this->assertFalse($categoryA->hasRole($unusedRole), 'CategoryA has UnusedRole');
        $this->assertFalse($categoryA->hasPermission($unusedPermission));
        // For groupA
        $this->assertFalse($groupA->hasRole($roleForUser), 'GroupA has RoleForUser');
        $this->assertFalse($groupA->hasPermission($permissionForUser), 'GroupA has PermissionForUser');
        $this->assertTrue($groupA->hasRole($roleForCategory), 'GroupA has RoleForCategory');
        $this->assertTrue($groupA->hasPermission($permissionForCategory), 'GroupA has PermissionForCategory');
        $this->assertTrue($groupA->hasRole($roleA), 'GroupA has RoleA');
        $this->assertTrue($groupA->hasPermission($permissionA));
        $this->assertFalse($groupA->hasRole($roleB), 'GroupA has RoleB');
        $this->assertFalse($groupA->hasPermission($permissionB));
        $this->assertFalse($groupA->hasRole($unusedRole), 'GroupA has UnusedRole');
        $this->assertFalse($groupA->hasPermission($unusedPermission));
        // For groupB
        $this->assertFalse($groupB->hasRole($roleForUser), 'GroupB has RoleForUser');
        $this->assertFalse($groupB->hasPermission($permissionForUser), 'GroupB has PermissionForUser');
        $this->assertFalse($groupB->hasRole($roleForCategory), 'GroupB has RoleForCategory');
        $this->assertFalse($groupB->hasPermission($permissionForCategory), 'GroupB has PermissionForCategory');
        $this->assertFalse($groupB->hasRole($roleA), 'GroupB has RoleA');
        $this->assertFalse($groupB->hasPermission($permissionA));
        $this->assertTrue($groupB->hasRole($roleB), 'GroupB has RoleB');
        $this->assertTrue($groupB->hasPermission($permissionB));
        $this->assertFalse($groupB->hasRole($unusedRole), 'GroupB has UnusedRole');
        $this->assertFalse($groupB->hasPermission($unusedPermission));
        // For unusedGroup
        $this->assertFalse($unusedGroup->hasRole($roleForUser), 'UnusedGroup has RoleForUser');
        $this->assertFalse($unusedGroup->hasPermission($permissionForUser), 'UnusedGroup has PermissionForUser');
        $this->assertFalse($unusedGroup->hasRole($roleForCategory), 'UnusedGroup has RoleForCategory');
        $this->assertFalse($unusedGroup->hasPermission($permissionForCategory), 'UnusedGroup has PermissionForCategory');
        $this->assertFalse($unusedGroup->hasRole($roleA), 'UnusedGroup has RoleA');
        $this->assertFalse($unusedGroup->hasPermission($permissionA));
        $this->assertFalse($unusedGroup->hasRole($roleB), 'UnusedGroup has RoleB');
        $this->assertFalse($unusedGroup->hasPermission($permissionB));
        $this->assertTrue($unusedGroup->hasRole($unusedRole), 'UnusedGroup has UnusedRole');
        $this->assertTrue($unusedGroup->hasPermission($unusedPermission));
        // For user
        $this->assertTrue($user->hasRole($roleForUser), 'User has RoleForUser');
        $this->assertTrue($user->hasPermission($permissionForUser), 'User has PermissionForUser');
        $this->assertTrue($user->hasRole($roleForCategory), 'User has RoleForCategory');
        $this->assertTrue($user->hasPermission($permissionForCategory), 'User has PermissionForCategory');
        $this->assertTrue($user->hasRole($roleA), 'User has RoleA');
        $this->assertTrue($user->hasPermission($permissionA));
        $this->assertTrue($user->hasRole($roleB), 'User has RoleB');
        $this->assertTrue($user->hasPermission($permissionB));
        $this->assertFalse($user->hasRole($unusedRole), 'User has UnusedRole');
        $this->assertFalse($user->hasPermission($unusedPermission));

    }


}
