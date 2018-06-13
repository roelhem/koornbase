<?php

namespace Tests\Unit\Rbac;

use Roelhem\RbacGraph\Builders\RbacBuilder;
use Roelhem\RbacGraph\Contracts\Builder as BuilderContract;
use Roelhem\RbacGraph\Contracts\Builder;
use Roelhem\RbacGraph\Contracts\Graph as GraphContract;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Exceptions\EdgeNotAllowedException;
use Tests\TestCase;

class RbacBuilderTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNewInstance()
    {
        $builder = new RbacBuilder();

        $this->assertInstanceOf(GraphContract::class, $builder);
        $this->assertInstanceOf(BuilderContract::class, $builder);
    }

    public function testNodesAndEdges()
    {
        $builder = new RbacBuilder();

        $this->assertCount(0, $builder->getNodes());
        $this->assertCount(0, $builder->getEdges());

        $role = $builder->role('role');
        $permission = $builder->permission('permission');

        $this->assertCount(2, $builder->getNodes());
        $this->assertCount(0, $builder->getEdges());

        $role->assign($permission);

        $this->assertCount(2, $builder->getNodes());
        $this->assertCount(1, $builder->getEdges());

        $this->assertTrue($builder->hasNode('role'));
        $this->assertTrue($builder->hasNode('permission'));
        $this->assertFalse($builder->hasNode('undefined'));
        $this->assertTrue($builder->hasEdge('role','permission'));
        $this->assertFalse($builder->hasEdge('permission','role'));
    }

    public function testGroupPrefixes()
    {
        $builder = new RbacBuilder();

        $builder->role('role_1');

        $builder->group('group.', function(Builder $builder) {
            $builder->role('role_2');
            $builder->role('role_1');

            $builder->group('subgroup.', function(Builder $builder) {
                $builder->role('role_3');
                $builder->role('role_2');
            });

            $builder->permission('permission_1');
        });

        $builder->role('permission_2');

        $this->assertTrue($builder->hasNode('role_1'));
        $this->assertFalse($builder->hasNode('role_2'));
        $this->assertFalse($builder->hasNode('role_3'));
        $this->assertFalse($builder->hasNode('permission_1'));
        $this->assertTrue($builder->hasNode('permission_2'));

        $this->assertFalse($builder->hasNode('group.role_1'));
        $this->assertTrue($builder->hasNode('group.role_2'));
        $this->assertFalse($builder->hasNode('group.role_3'));
        $this->assertTrue($builder->hasNode('group.permission_1'));
        $this->assertFalse($builder->hasNode('group.permission_2'));

        $this->assertFalse($builder->hasNode('group.subgroup.role_1'));
        $this->assertFalse($builder->hasNode('group.subgroup.role_2'));
        $this->assertTrue($builder->hasNode('group.subgroup.role_3'));
        $this->assertFalse($builder->hasNode('group.subgroup.permission_1'));
        $this->assertFalse($builder->hasNode('group.subgroup.permission_2'));
    }

    public function testEdgeTypeConstraints()
    {
        $builder = new RbacBuilder();

        $role = $builder->role('role');
        $permission = $builder->permission('permission');

        $this->assertTrue($builder->hasNode('role'));
        $this->assertTrue($builder->hasNode('permission'));

        $role->assign($permission);
        try {
            $permission->assign($role);
            $this->assertFalse(true, 'Geen error gegeven!');
        } catch (EdgeNotAllowedException $exception) {
            $this->assertInstanceOf(EdgeNotAllowedException::class, $exception);
        }

        $this->assertTrue($builder->hasEdge($role, $permission));
        $this->assertFalse($builder->hasEdge($permission, $role));
        $this->assertCount(1, $builder->getEdges());
    }
}
