<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 13:30
 */

namespace Unit\Graphs;


use Roelhem\RbacGraph\Contracts\Graphs\Graph;
use Roelhem\RbacGraph\Contracts\Graphs\SubGraph;
use Roelhem\RbacGraph\Graphs\Tools\Filters\CallbackNodeFilter;
use Roelhem\RbacGraph\Graphs\DictionaryGraph;
use Roelhem\RbacGraph\Graphs\SubGraphs\NodeFilterSubGraph;
use Roelhem\RbacGraph\Tests\TestCase;

class NodeFilterSubGraphTest extends TestCase
{

    public function testInstance() {
        $graph = new DictionaryGraph();
        $filter = new CallbackNodeFilter($graph, function($node) { return true; });
        $subA = new NodeFilterSubGraph($graph,$filter);
        $subB = new NodeFilterSubGraph($graph, function($node) { return true; });

        $this->assertInstanceOf(Graph::class, $subA);
        $this->assertInstanceOf(SubGraph::class, $subA);
        $this->assertInstanceOf(NodeFilterSubGraph::class, $subA);
        $this->assertInstanceOf(NodeFilterSubGraph::class, $subB);
    }


    /**
     * @throws \Roelhem\RbacGraph\Exceptions\NodeNotFoundException
     */
    public function testUsage() {
        $graph = new DictionaryGraph();
        $b = $graph->builder();

        $b->role('A');
        $b->role('B')->assignTo('A');
        $b->role('C')->assignTo('A','B');


        $this->assertCount(3, $graph->getNodes());
        $this->assertCount(3, $graph->getEdges());

        $sub = new NodeFilterSubGraph($graph, function(string $node) {
            return $node === 'A' || $node === 'C';
        });

        $this->assertCount(2, $sub->getNodes());
        $this->assertCount(1, $sub->getEdges());

        $this->assertTrue($sub->hasEdge('A','C'));
        $this->assertFalse($sub->hasNode('B'));
    }

    public function testMultiple() {
        $graph = new DictionaryGraph();
        $b = $graph->builder();

        $b->role('A');
        $b->task('B')->assignTo('A');
        $b->permissionSet('C')->assignTo('B');
        $b->permission('D')->assignTo('C');

        $this->assertCount(4, $graph->getNodes());
        $this->assertCount(3, $graph->getEdges());

        $subA = new NodeFilterSubGraph($graph, function(string $node) {
            return $node !== 'D';
        });

        $this->assertCount(3, $subA->getNodes());
        $this->assertCount(2, $subA->getEdges());

        $filter = new CallbackNodeFilter($graph, function(string $node) {
            return $node !== 'C';
        });

        $subB = new NodeFilterSubGraph($graph, $filter);

        $subAB = new NodeFilterSubGraph($subA, $filter);

        $this->assertCount(3, $subB->getNodes());
        $this->assertCount(1, $subB->getEdges());

        $this->assertCount(2, $subAB->getNodes());
        $this->assertCount(1, $subAB->getEdges());


    }

}