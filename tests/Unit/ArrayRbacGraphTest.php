<?php

namespace Tests\Unit;

use App\Services\Rbac\ArrayRbacGraph;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArrayRbacGraphTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     * @throws
     */
    public function testGraph()
    {
        $graph = new ArrayRbacGraph();

        $this->assertFalse($graph->modelExists('modelA'));
        $this->assertFalse($graph->roleExists('roleA'));
        $this->assertFalse($graph->permissionExists('permissionA'));

        $graph->modelCreate('modelA');
        $graph->roleCreate('roleA');
        $graph->permissionCreate('permissionA');

        $this->assertTrue($graph->modelExists('modelA'));
        $this->assertTrue($graph->roleExists('roleA'));
        $this->assertTrue($graph->permissionExists('permissionA'));

        $this->assertEquals('modelA', $graph->modelGetId('modelA'));
        $this->assertEquals('roleA', $graph->roleGetId('roleA'));
        $this->assertEquals('permissionA', $graph->permissionGetId('permissionA'));

        $graph->modelCreate('modelB');
        $graph->roleCreate('roleB');
        $graph->permissionCreate('permissionB');

        $this->assertTrue($graph->roleEquals('roleA', 'roleA'));
        $this->assertFalse($graph->roleEquals('roleA', 'roleB'));
        $this->assertTrue($graph->permissionEquals('permissionA', 'permissionA'));
        $this->assertFalse($graph->roleEquals('permissionA', 'permissionB'));

        $graph->roleCreate('childRoleA1');
        $graph->roleCreate('childRoleA2');
        $graph->roleAddChildRole('roleA','childRoleA1');
        $graph->roleAddChildRole('roleA','childRoleA2');
        $graph->roleCreate('childRoleB');
        $graph->roleAddChildRole('roleB','childRoleB');

        $this->assertCount(2, $graph->roleGetChildRoles('roleA'));
        $this->assertContains('childRoleA1', $graph->roleGetChildRoles('roleA'));
        $this->assertContains('childRoleA2', $graph->roleGetChildRoles('roleA'));
        $this->assertCount(1, $graph->roleGetChildRoles('roleB'));
        $this->assertContains('childRoleB', $graph->roleGetChildRoles('roleB'));

        $graph->roleAddChildPermission('roleA', 'permissionA');
        $graph->roleAddChildPermission('roleA', 'permissionB');

        $this->assertCount(2, $graph->roleGetChildPermissions('roleA'));
        $this->assertContains('permissionA', $graph->roleGetChildPermissions('roleA'));
        $this->assertContains('permissionB', $graph->roleGetChildPermissions('roleA'));

        $graph->permissionCreate('childPermissionA');
        $graph->permissionAddChildPermission('permissionA', 'childPermissionA');

        $this->assertEquals(['childPermissionA'], $graph->permissionGetChildPermissions('permissionA'));

        $graph->modelAddChildRole('modelA', 'roleA');

        $this->assertEquals(['roleA'], $graph->modelGetChildRoles('modelA'));

        $graph->modelAddInheritModel('modelA','modelB');

        $this->assertEquals(['modelB'], $graph->modelGetInheritModels('modelA'));
    }
}
