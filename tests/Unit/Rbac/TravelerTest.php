<?php

namespace Tests\Unit\Rbac;

use Roelhem\RbacGraph\Builders\RbacBuilder;
use Roelhem\RbacGraph\Contracts\Edge;
use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Database\DatabaseGraph;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Iterators\BreathFirstGraphIterator;
use Roelhem\RbacGraph\Iterators\DepthFirstGraphIterator;
use Roelhem\RbacGraph\Traveler;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TravelerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $b = new RbacBuilder();

        $b->role('superParentRole');
        $b->role('parentRoleA')->assignTo('superParentRole');
        $b->role('parentRoleB')->assignTo('superParentRole');
        $b->role('subParentRoleA')->assignTo('parentRoleA');
        $b->role('roleA')->assignTo('subParentRoleA');
        $b->role('roleB')->assignTo('parentRoleB');
        $b->role('roleC')->assignTo('parentRoleA','parentRoleB');
        $b->role('roleD');

        $b->permission('permissionA')->assignTo('roleA');
        $b->permission('permissionB')->assignTo('roleB');
        $b->permission('permissionC')->assignTo('roleC');
        $b->permission('permissionD')->assignTo('roleD');

        $b->group('perm-group.', function(RbacBuilder $b) {

            $b->permission('one');
            $b->permission('two');
            $b->permission('tree');
            $b->permission('four');

            $b->permission('all')
                ->assign('one','two',['tree','perm-group.four'])
                ->assignTo('roleA','roleB');
        });



        $b->group('cycle.', function(RbacBuilder $b) {
            $b->role('X');
            $b->role('Y')->assignTo('X');
            $b->role('Z')->assignTo('Y');

            $b->permission('a')->assignTo('Z');
            $b->permission('b')->assignTo('a');
            $b->permission('c')->assignTo('b');

            $b->group('and.', function(RbacBuilder $b) {
                $b->permission('d');
                $b->create(NodeType::PERMISSION, 'c');

                $b->role('test')->assign('d','and.c');
            });

        });

        $this->assertTrue($b->hasEdge('perm-group.all','perm-group.one'));
        $this->assertTrue($b->hasEdge('perm-group.all','perm-group.two'));
        $this->assertTrue($b->hasEdge('perm-group.all','perm-group.tree'));
        $this->assertTrue($b->hasEdge('perm-group.all','perm-group.four'));

        $graph = resolve(DatabaseGraph::class);

        if(!($graph instanceof DatabaseGraph)) {
            $this->assertTrue(false);
        }

        $graph->addNodes($b->getNodes());
        $graph->addEdges($b->getEdges());
    }
}
