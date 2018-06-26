<?php

namespace Roelhem\RbacGraph\Tests\Unit;

use Roelhem\RbacGraph\Services\Builders\RbacBuilder;
use Roelhem\RbacGraph\Contracts\Services\Builder;
use Roelhem\RbacGraph\Graphs\DictionaryGraph;
use Roelhem\RbacGraph\Tests\TestCase;

class BuilderTest extends TestCase
{

    public function testInstance() {

        $graph = new DictionaryGraph();
        $builder = new RbacBuilder($graph);

        $this->assertInstanceOf(Builder::class, $builder);
    }

    /**
     * @throws \Roelhem\RbacGraph\Exceptions\NodeNotFoundException
     */
    public function testBuilding() {

        $graph = new DictionaryGraph();
        $builder = new RbacBuilder($graph);

        $this->assertCount(0, $graph->getNodes());

        $builder->role('role');
        $builder->task('task');
        $builder->permission('permission');

        $this->assertCount(3, $graph->getNodes());

        $this->assertTrue($graph->hasNode('role'));
        $this->assertTrue($graph->hasNode('task'));
        $this->assertFalse($graph->hasNode('perm'));

        $builder->role('role')->title('Rol');

        $this->assertCount(3, $graph->getNodes());

        $this->assertEquals('Rol', $graph->getNode('role')->getTitle());

        $builder->role('role')->assign('task');
        $builder->get('permission')->assignTo($builder->task('task'));

        $this->assertCount(2, $graph->getEdges());

        $this->assertTrue($graph->hasEdge('role', 'task'));
        $this->assertTrue($graph->hasEdge('task', 'permission'));
        $this->assertFalse($graph->hasEdge('role', 'permission'));

    }

    public function testPrefixGroups() {
        $graph = new DictionaryGraph();
        $builder = new RbacBuilder($graph);

        $builder->role('a');

        $builder->group('test.', function(Builder $builder) {
            $builder->permission('x');
            $builder->role('a')->assign('x');
        });

        $this->assertTrue($graph->hasNode('a'));
        $this->assertTrue($graph->hasNode('test.x'));
        $this->assertFalse($graph->hasNode('test.a'));
        $this->assertTrue($graph->hasEdge('a','test.x'));

    }

}