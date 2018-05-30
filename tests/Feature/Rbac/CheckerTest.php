<?php

namespace Tests\Feature\Rbac;

use App\Services\Rbac\ArrayRbacGraph;
use App\Services\Rbac\SimpleRbacChecker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckerTest extends TestCase
{

    /**
     * Tests if a role has the right roles and permissions
     *
     * @param $role
     * @param $roles
     * @param $permissions
     * @dataProvider roleProvider
     */
    public function testRoleRolesAndPermissions($role, $roles, $permissions) {
        $checker = new SimpleRbacChecker($this->getTestGraph());

        foreach($roles as $checkRole => $expectedValue) {
            $this->assertEquals(
                $expectedValue,
                $checker->roleHasRole($role, $checkRole),
                "role $role has role $checkRole"
            );
        }

        foreach ($permissions as $permission => $expectedValue) {
            $this->assertEquals(
                $expectedValue,
                $checker->roleHasPermission($role, $permission),
                "role $role has permission $permission"
            );
        }
    }

    /**
     * Tests if a permission has the right permissions.
     *
     * @param string $permission,
     * @param array $checkPermissions,
     * @dataProvider permissionProvider
     */
    public function testPermissionPermissions($permission, $checkPermissions) {
        $checker = new SimpleRbacChecker($this->getTestGraph());

        foreach ($checkPermissions as $checkPermission => $expectedValue) {
            $this->assertEquals(
                $expectedValue,
                $checker->permissionHasPermission($permission, $checkPermission),
                "permission $permission has permission $checkPermission"
            );
        }
    }

    /**
     * Tests if a model has the right roles and permissions
     *
     * @param string $model
     * @param array $roles
     * @param array $permissions
     * @return void
     * @dataProvider modelProvider
     */
    public function testModelRolesAndPermissions($model, $roles, $permissions)
    {
        $checker = new SimpleRbacChecker($this->getTestGraph());

        foreach($roles as $role => $expectedValue) {
            $this->assertEquals(
                $expectedValue,
                $checker->modelHasRole($model, $role),
                "model $model has role $role"
            );
        }

        foreach ($permissions as $permission => $expectedValue) {
            $this->assertEquals(
                $expectedValue,
                $checker->modelHasPermission($model, $permission),
                "model $model has permission $permission"
            );
        }

    }

    /**
     * @throws
     */
    protected function getTestGraph() {
        $graph = new ArrayRbacGraph();

        $graph->modelCreateMultiple(['userA','userB','groupA','groupB','groupC','groupD','categoryA','categoryB']);
        $graph->modelAddInheritModels('userA', ['groupA','groupB','groupC']);
        $graph->modelAddInheritModels('userB', ['groupC','groupD']);
        $graph->modelAddInheritModel('groupA', 'categoryA');
        $graph->modelAddInheritModel('groupC', 'categoryA');
        $graph->modelAddInheritModel('groupD', 'categoryB');

        $graph->roleCreateMultiple(['userRoleA','userRoleB','groupRoleA','groupRoleC','groupRoleD','categoryRoleA','categoryRoleB']);
        $graph->modelAddChildRole('userA','userRoleA');
        $graph->modelAddChildRole('userB','userRoleB');
        $graph->modelAddChildRole('groupA','groupRoleA');
        $graph->modelAddChildRole('groupC','groupRoleC');
        $graph->modelAddChildRole('groupD','groupRoleD');
        $graph->modelAddChildRole('categoryA','categoryRoleA');
        $graph->modelAddChildRole('categoryB','categoryRoleB');

        $graph->roleCreateMultiple(['userRoles','userSubRoleA1','userSubRoleA2','userSubSubRoleA1','categorySubRoleA']);
        $graph->roleAddChildRoles('userRoles', ['userRoleA','userRoleB']);
        $graph->roleAddChildRoles('userRoleA', ['userSubRoleA1','userSubRoleA2']);
        $graph->roleAddChildRoles('userSubRoleA1',['userSubSubRoleA1']);
        $graph->roleAddChildRoles('categoryRoleA',['categorySubRoleA']);

        $graph->permissionCreateMultiple(['permissionA','permissionB','permissionC','permissionD','permissionE']);
        $graph->permissionCreateMultiple(['permissionGroupA','permissionSubGroupA']);
        $graph->permissionAddChildPermissions('permissionGroupA',['permissionA','permissionSubGroupA']);
        $graph->permissionAddChildPermissions('permissionSubGroupA', ['permissionB','permissionC']);
        $graph->roleAddChildPermission('groupRoleA','permissionGroupA');
        $graph->roleAddChildPermission('categoryRoleA', 'permissionB');
        $graph->roleAddChildPermission('categoryRoleB','permissionE');

        return $graph;
    }

    /**
     * The expected values of some permissions.
     *
     * @return array
     */
    public function permissionProvider() {
        return [
            'permissionGroupA' => [
                'permissionGroupA',
                [
                    'permissionA' => true,
                    'permissionB' => true,
                    'permissionC' => true,
                    'permissionD' => false,
                    'permissionE' => false,
                    'permissionGroupA' => true,
                    'permissionSubGroupA' => true,
                ]
            ],
            'permissionSubGroupA' => [
                'permissionSubGroupA',
                [
                    'permissionA' => false,
                    'permissionB' => true,
                    'permissionC' => true,
                    'permissionD' => false,
                    'permissionE' => false,
                    'permissionGroupA' => false,
                    'permissionSubGroupA' => true,
                ]
            ],
            'permissionA' => [
                'permissionA',
                [
                    'permissionA' => true,
                    'permissionB' => false,
                    'permissionC' => false,
                    'permissionD' => false,
                    'permissionE' => false,
                    'permissionGroupA' => false,
                    'permissionSubGroupA' => false,
                ]
            ],
        ];
    }

    /**
     * The expected values of some roles
     *
     * @return array
     */
    public function roleProvider() {
        return [
            'userRoleA' => [
                'userRoleA',
                [
                    'userRoleA' => true,
                    'userRoleB' => false,
                    'groupRoleA' => false,
                    'groupRoleC' => false,
                    'groupRoleD' => false,
                    'categoryRoleA' => false,
                    'categoryRoleB' => false,
                    'userRoles' => false,
                    'userSubRoleA1' => true,
                    'userSubRoleA2' => true,
                    'userSubSubRoleA1' => true,
                    'categorySubRoleA' => false
                ],
                [
                    'permissionA' => false,
                    'permissionB' => false,
                    'permissionC' => false,
                    'permissionD' => false,
                    'permissionE' => false,
                    'permissionGroupA' => false,
                    'permissionSubGroupA' => false,
                ]
            ],
            'userRoleB' => [
                'userRoleB',
                [
                    'userRoleA' => false,
                    'userRoleB' => true,
                    'groupRoleA' => false,
                    'groupRoleC' => false,
                    'groupRoleD' => false,
                    'categoryRoleA' => false,
                    'categoryRoleB' => false,
                    'userRoles' => false,
                    'userSubRoleA1' => false,
                    'userSubRoleA2' => false,
                    'userSubSubRoleA1' => false,
                    'categorySubRoleA' => false
                ],
                [
                    'permissionA' => false,
                    'permissionB' => false,
                    'permissionC' => false,
                    'permissionD' => false,
                    'permissionE' => false,
                    'permissionGroupA' => false,
                    'permissionSubGroupA' => false,
                ]
            ],
            'userRoles' => [
                'userRoles',
                [
                    'userRoleA' => true,
                    'userRoleB' => true,
                    'groupRoleA' => false,
                    'groupRoleC' => false,
                    'groupRoleD' => false,
                    'categoryRoleA' => false,
                    'categoryRoleB' => false,
                    'userRoles' => true,
                    'userSubRoleA1' => true,
                    'userSubRoleA2' => true,
                    'userSubSubRoleA1' => true,
                    'categorySubRoleA' => false
                ],
                [
                    'permissionA' => false,
                    'permissionB' => false,
                    'permissionC' => false,
                    'permissionD' => false,
                    'permissionE' => false,
                    'permissionGroupA' => false,
                    'permissionSubGroupA' => false,
                ]
            ],
            'groupRoleA' => [
                'groupRoleA',
                [
                    'userRoleA' => false,
                    'userRoleB' => false,
                    'groupRoleA' => true,
                    'groupRoleC' => false,
                    'groupRoleD' => false,
                    'categoryRoleA' => false,
                    'categoryRoleB' => false,
                    'userRoles' => false,
                    'userSubRoleA1' => false,
                    'userSubRoleA2' => false,
                    'userSubSubRoleA1' => false,
                    'categorySubRoleA' => false
                ],
                [
                    'permissionA' => true,
                    'permissionB' => true,
                    'permissionC' => true,
                    'permissionD' => false,
                    'permissionE' => false,
                    'permissionGroupA' => true,
                    'permissionSubGroupA' => true,
                ]
            ]
        ];
    }

    /**
     * The expected values of some models
     *
     * @return array
     */
    public function modelProvider() {
        return [
            'userA' => [
                'userA',
                [
                    'userRoleA' => true,
                    'userRoleB' => false,
                    'groupRoleA' => true,
                    'groupRoleC' => true,
                    'groupRoleD' => false,
                    'categoryRoleA' => true,
                    'categoryRoleB' => false,
                    'userRoles' => false,
                    'userSubRoleA1' => true,
                    'userSubRoleA2' => true,
                    'userSubSubRoleA1' => true,
                    'categorySubRoleA' => true
                ],
                [
                    'permissionA' => true,
                    'permissionB' => true,
                    'permissionC' => true,
                    'permissionD' => false,
                    'permissionE' => false,
                    'permissionGroupA' => true,
                    'permissionSubGroupA' => true,
                ]
            ],
            'userB' => [
                'userB',
                [
                    'userRoleA' => false,
                    'userRoleB' => true,
                    'groupRoleA' => false,
                    'groupRoleC' => true,
                    'groupRoleD' => true,
                    'categoryRoleA' => true,
                    'categoryRoleB' => true,
                    'userRoles' => false,
                    'userSubRoleA1' => false,
                    'userSubRoleA2' => false,
                    'userSubSubRoleA1' => false,
                    'categorySubRoleA' => true
                ],
                [
                    'permissionA' => false,
                    'permissionB' => true,
                    'permissionC' => false,
                    'permissionD' => false,
                    'permissionE' => true,
                    'permissionGroupA' => false,
                    'permissionSubGroupA' => false,
                ]
            ],
            'groupA' => [
                'groupA',
                [
                    'userRoleA' => false,
                    'userRoleB' => false,
                    'groupRoleA' => true,
                    'groupRoleC' => false,
                    'groupRoleD' => false,
                    'categoryRoleA' => true,
                    'categoryRoleB' => false,
                    'userRoles' => false,
                    'userSubRoleA1' => false,
                    'userSubRoleA2' => false,
                    'userSubSubRoleA1' => false,
                    'categorySubRoleA' => true
                ],
                [
                    'permissionA' => true,
                    'permissionB' => true,
                    'permissionC' => true,
                    'permissionD' => false,
                    'permissionE' => false,
                    'permissionGroupA' => true,
                    'permissionSubGroupA' => true,
                ]
            ],
            'groupB' => [
                'groupB',
                [
                    'userRoleA' => false,
                    'userRoleB' => false,
                    'groupRoleA' => false,
                    'groupRoleC' => false,
                    'groupRoleD' => false,
                    'categoryRoleA' => false,
                    'categoryRoleB' => false,
                    'userRoles' => false,
                    'userSubRoleA1' => false,
                    'userSubRoleA2' => false,
                    'userSubSubRoleA1' => false,
                    'categorySubRoleA' => false
                ],
                [
                    'permissionA' => false,
                    'permissionB' => false,
                    'permissionC' => false,
                    'permissionD' => false,
                    'permissionE' => false,
                    'permissionGroupA' => false,
                    'permissionSubGroupA' => false,
                ]
            ],
            'categoryA' => [
                'categoryA',
                [
                    'userRoleA' => false,
                    'userRoleB' => false,
                    'groupRoleA' => false,
                    'groupRoleC' => false,
                    'groupRoleD' => false,
                    'categoryRoleA' => true,
                    'categoryRoleB' => false,
                    'userRoles' => false,
                    'userSubRoleA1' => false,
                    'userSubRoleA2' => false,
                    'userSubSubRoleA1' => false,
                    'categorySubRoleA' => true
                ],
                [
                    'permissionA' => false,
                    'permissionB' => true,
                    'permissionC' => false,
                    'permissionD' => false,
                    'permissionE' => false,
                    'permissionGroupA' => false,
                    'permissionSubGroupA' => false,
                ]
            ]
        ];
    }
}
