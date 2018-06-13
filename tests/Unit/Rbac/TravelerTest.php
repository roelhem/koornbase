<?php

namespace Tests\Unit\Rbac;

use Roelhem\RbacGraph\Builders\RbacBuilder;
use Roelhem\RbacGraph\Contracts\Edge;
use Roelhem\RbacGraph\Contracts\Node;
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
                ->assign('perm-group.one','perm-group.two',['perm-group.tree','perm-group.four'])
                ->assignTo('roleA','roleB');
        });

        print_r($b->getNodes()->map(function(Node $node) {
            return $node->getType().": ".$node->getName();
        }));

        print_r($b->getEdges()->map(function(Edge $edge) {
            $parent = $edge->getParent();
            $child = $edge->getChild();
            $parentString = '[ '.$parent->getType().": ".$parent->getName().' ]';
            $childString = '[ '.$child->getType().": ".$child->getName().' ]';
            return str_pad($parentString,40).' -> '.str_pad($childString, 40);
        }));

        $traveler = new Traveler($b);

        foreach ($traveler->depthFirst('superParentRole') as $node) {
            if($node instanceof Node) {
                echo "[ {$node->getType()->getName()}: {$node->getName()} ]\n -> ";
            }
        }
    }
}
