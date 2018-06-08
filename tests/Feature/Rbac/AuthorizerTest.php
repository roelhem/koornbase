<?php

namespace Tests\Feature\Rbac;

use App\Contracts\Rbac\RbacAuthorizer;
use App\Interfaces\Rbac\RbacNode;
use App\Interfaces\Rbac\RbacPermission;
use App\Interfaces\Rbac\RbacRole;
use App\Interfaces\Rbac\RbacRoleAuthorizable;
use App\Services\Rbac\Authorizers\SimpleRbacAuthorizer;
use App\Services\Rbac\SimpleGraph\Graph;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorizerTest extends TestCase
{

    public function authorizerProvider() {
        return [
            'SIMPLE_AUTHORIZER' => [SimpleRbacAuthorizer::class]
        ];
    }

    /**
     * A basic test example.
     *
     * @param string $authorizerClassName
     * @return void
     * @dataProvider authorizerProvider
     * @throws
     */
    public function testAuthorizers($authorizerClassName)
    {
        $authorizer = resolve($authorizerClassName);

        $this->assertInstanceOf(RbacAuthorizer::class, $authorizer);

        $graph = new Graph($authorizer);

        $this->assertEquals($authorizer, $graph->authorizer());

        // SIMPLE ROLE BEHAVIOR
        $roleA = $graph->role('a');
        $roleB = $graph->role('b');

        $this->assertTrue($roleA->hasRole('a'));
        $this->assertFalse($roleA->hasRole('b'));
        $this->assertFalse($roleA->hasRole($roleB));
        $this->assertTrue($roleB->hasRole($roleB));
        $this->assertFalse($roleB->hasRole('a'));

        $this->assertTrue($roleA->hasRoles(['a']));
        $this->assertFalse($roleA->hasRoles(['a','b']));
        $this->assertTrue($roleA->hasRoles(['a','b'], true));
        $this->assertFalse($roleA->hasRoles(['b'], true));

        // SIMPLE PERMISSION BEHAVIOR
        $permissionA = $graph->permission('A');
        $permissionB = $graph->permission('B');

        $this->assertTrue($permissionA->hasPermission('A'));
        $this->assertFalse($permissionA->hasPermission('B'));
        $this->assertFalse($permissionA->hasPermission($permissionB));
        $this->assertTrue($permissionB->hasPermission($permissionB));
        $this->assertFalse($permissionB->hasPermission('A'));

        $this->assertTrue($permissionA->hasPermissions(['A']));
        $this->assertFalse($permissionA->hasPermissions(['A','B']));
        $this->assertTrue($permissionA->hasPermissions(['A','B'], true));
        $this->assertFalse($permissionA->hasPermissions(['B'], true));

        // SIMPLE PERMISSION TO ROLE ASSIGNMENT
        $roleA->assignPermission('A');
        $roleB->assignPermission($permissionB);

        $this->assertTrue($roleA->hasPermission('A'));
        $this->assertFalse($roleA->hasPermission('B'));
        $this->assertTrue($roleB->hasPermission($permissionB));
        $this->assertFalse($roleB->hasPermission($permissionA));

        $this->assertTrue($roleA->hasPermissions(['A']));
        $this->assertFalse($roleA->hasPermissions(['A','B']));
        $this->assertTrue($roleA->hasPermissions(['A','B'], true));
        $this->assertFalse($roleA->hasPermissions(['B'], true));

        // COMPLEX ROLE STRUCTURE
        $roleC = $graph->role('c')->assignToRole($roleA);
        $roleD = $graph->role('d')->assignToRole($roleC);
        $roleX = $graph->role('x');
        $roleY = $graph->role('y')->assignToRole($roleX);
        $roleZ = $graph->role('z')->assignToRole($roleX);
        $roleParent = $graph->role('parent')->assignAll([
            $roleA, $roleB, $roleX
        ]);
        $roleEmpty = $graph->role('empty');

        $this->assertTrue($roleA->hasRole($roleC));
        $this->assertTrue($roleA->hasRole($roleD));
        $this->assertFalse($roleA->hasRole($roleX));

        $this->assertTrue($roleX->hasRole('y'));
        $this->assertTrue($roleX->hasRole('z'));
        $this->assertFalse($roleZ->hasRole('y'));

        $this->assertTrue($roleA->hasRoles(['a','c','d']));
        $this->assertFalse($roleA->hasRoles(['x','y','z','b','parent'], true));
        $this->assertTrue($roleB->hasRoles(['b']));
        $this->assertFalse($roleB->hasRoles(['x','y','z','a','c','d','parent'], true));
        $this->assertTrue($roleC->hasRoles(['c','d']));
        $this->assertFalse($roleC->hasRoles(['x','y','z','a','b','parent'], true));
        $this->assertTrue($roleD->hasRoles(['d']));
        $this->assertFalse($roleD->hasRoles(['x','y','z','a','b','c','parent'], true));
        $this->assertTrue($roleX->hasRoles(['x','y','z']));
        $this->assertFalse($roleX->hasRoles(['a','b','c','parent'], true));
        $this->assertTrue($roleY->hasRoles(['y']));
        $this->assertFalse($roleY->hasRoles(['x','z','a','b','c','parent'], true));
        $this->assertTrue($roleZ->hasRoles(['z']));
        $this->assertFalse($roleZ->hasRoles(['x','y','a','b','c','parent'], true));
        $this->assertTrue($roleParent->hasRoles(['a','b','c','d','x','y','z','parent']));
        $this->assertFalse($roleParent->hasRoles([], true));

        // COMPLEX PERMISSION STRUCTURE
        $permissionC = $graph->permission('C')->assignToPermission($permissionA);
        $permissionD = $graph->permission('D')->assignToPermission($permissionC);
        $permissionE = $graph->permission('E')->assignToPermission($permissionD)->assignToPermission($permissionB);
        $permissionX = $graph->permission('X');
        $permissionY = $graph->permission('Y')->assignToRole('y')->assignToPermission('X');
        $permissionZ = $graph->permission('Z')->assignToRole('z')->assignToPermission('X');

        $this->assertFalse($permissionA->hasPermission('B'));
        $this->assertTrue($permissionA->hasPermission('C'));
        $this->assertTrue($permissionA->hasPermission('D'));
        $this->assertTrue($permissionA->hasPermission('E'));

        $this->assertTrue($permissionB->hasPermission($permissionB));
        $this->assertFalse($permissionB->hasPermission($permissionC));
        $this->assertFalse($permissionB->hasPermission($permissionD));
        $this->assertTrue($permissionB->hasPermission($permissionE));

        $this->assertFalse($permissionA->hasPermissions(['X','Y','Z'], true));
        $this->assertTrue($permissionX->hasPermissions(['X','Y','Z']));

        // CHECK THE COMPLEX ROLE TO PERMISSION STRUCTURE
        $this->assertTrue($roleA->hasPermission($permissionD));
        $this->assertTrue($roleA->hasPermission($permissionE));
        $this->assertFalse($roleB->hasPermission($permissionD));
        $this->assertTrue($roleB->hasPermission($permissionE));

        $this->assertTrue($roleX->hasPermission($permissionY));
        $this->assertTrue($roleX->hasPermission($permissionZ));
        $this->assertFalse($roleX->hasPermission($permissionX));

        $this->assertFalse($roleParent->hasPermission('X'));


        // CHECK THE GET ROLES FUNCTION
        $this->assertAreIds(['a','c','d'],$roleA->getRoles());
        $this->assertAreIds(['b'], $roleB->getRoles());
        $this->assertAreIds(['c','d'], $roleC->getRoles());
        $this->assertAreIds(['d'], $roleD->getRoles());
        $this->assertAreIds(['x','y','z'], $roleX->getRoles());
        $this->assertAreIds(['y'], $roleY->getRoles());
        $this->assertAreIds(['z'], $roleZ->getRoles());
        $this->assertAreIds(['a','b','c','d','x','y','z','parent'], $roleParent->getRoles());
        $this->assertAreIds(['empty'], $roleEmpty->getRoles());




        // CHECK THE GET PERMISSIONS FUNCTION
        $this->assertAreIds(['A','C','D','E'], $roleA->getPermissions());
        $this->assertAreIds(['B','E'], $roleB->getPermissions());
        $this->assertAreIds(['Y','Z'], $roleX->getPermissions());
        $this->assertAreIds(['A','B','C','D','E','Y','Z'], $roleParent->getPermissions());
        $this->assertAreIds([], $roleEmpty->getPermissions());

        $this->assertAreIds(['A','C','D','E'], $permissionA->getPermissions());
        $this->assertAreIds(['C','D','E'], $permissionC->getPermissions());
        $this->assertAreIds(['B','E'], $permissionB->getPermissions());


        // CHECK THE PERMISSIONS
        $modelMaster = $graph->model('Master')->assignRole('z');
        $modelA = $graph->model('ModelA')->assignRole($roleA)->addInheritSource($modelMaster);
        $modelB = $graph->model('ModelB');
        $roleB->assignTo($modelB);
        $modelEmpty = $graph->model('EmptyModel');


        $this->assertTrue($modelMaster->hasRole('z'));
        $this->assertFalse($modelMaster->hasRole('a'));
        $this->assertTrue($modelMaster->hasPermission('Z'));
        $this->assertFalse($modelMaster->hasPermission('E'));

        $this->assertTrue($modelA->hasRole('d'));
        $this->assertTrue($modelA->hasPermission('D'));
        $this->assertFalse($modelB->hasRole('d'));
        $this->assertFalse($modelB->hasPermission('D'));
        $this->assertTrue($modelA->hasPermission('E'));
        $this->assertTrue($modelB->hasPermission('E'));

        $this->assertTrue($modelA->hasRole('z'));
        $this->assertFalse($modelB->hasRole('z'));
        $this->assertTrue($modelA->hasPermission('Z'));
        $this->assertFalse($modelB->hasPermission('Z'));

        $this->assertAreIds(['a','c','d','z'], $modelA->getRoles());
        $this->assertAreIds(['b'], $modelB->getRoles());

        $this->assertAreIds(['A','C','D','E','Z'], $modelA->getPermissions());
        $this->assertAreIds(['B','E'], $modelB->getPermissions());

        $this->assertFalse($modelEmpty->hasPermissions(['A','B','C','D','E','Y','X','Z'], false));
        $this->assertAreIds([], $modelEmpty->getRoles());
        $this->assertAreIds([], $modelEmpty->getPermissions());

    }

    public function assertAreIds($ids, $nodes) {
        $this->assertTrue(is_iterable($nodes));
        $this->assertTrue(is_array($ids));

        $ids_string = '['.implode(', ', $ids).']';

        $nodes_items = collect($nodes)->map(function($node) {
            if(is_string($node)) {
                return "'$node'";
            } elseif($node instanceof RbacNode) {
                $res = '';
                if($node instanceof RbacRole) {
                    $res .= 'RbacRole: ';
                } elseif($node instanceof RbacPermission) {
                    $res .= 'RbacPermission: ';
                } else {
                    $res .= 'RbacNode: ';
                }
                $res .= "'".$node->getId()."'";
                return $res;
            } else {
                return get_class($node);
            }
        })->all();

        $nodes_items_string = '['.implode(', ', $nodes_items).']';

        $this->assertEquals(count($nodes), count($ids), "IDS: $ids_string, NODES: $nodes_items_string");


        foreach ($nodes as $node) {
            if($node instanceof RbacNode) {
                $nodeId = $node->getId();
            } else {
                $nodeId = strval($node);
            }

            $key = array_search($nodeId, $ids);

            $this->assertNotFalse($key,"The id $nodeId does not exist in  IDS: $ids_string, NODES: $nodes_items_string");

            unset($ids[$key]);
        }

        $this->assertCount(0, $ids, "IDS: $ids_string, NODES: $nodes_items_string");
    }
}
