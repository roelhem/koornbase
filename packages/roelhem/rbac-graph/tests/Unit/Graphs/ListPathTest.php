<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 14:04
 */

namespace Unit\Graphs;


use Roelhem\RbacGraph\Contracts\Graphs\Graph;
use Roelhem\RbacGraph\Contracts\Graphs\Path;
use Roelhem\RbacGraph\Contracts\Graphs\SubGraph;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Graphs\DictionaryGraph;
use Roelhem\RbacGraph\Graphs\Paths\ListPath;
use Roelhem\RbacGraph\Tests\TestCase;

class ListPathTest extends TestCase
{

    public function testInstance() {

        $graph = new DictionaryGraph();

        $path = new ListPath($graph);

        $this->assertInstanceOf(Graph::class, $path);
        $this->assertInstanceOf(SubGraph::class, $path);
        $this->assertInstanceOf(Path::class, $path);
        $this->assertInstanceOf(ListPath::class, $path);
    }

    /**
     * @throws \Roelhem\RbacGraph\Exceptions\PathEmptyException
     * @throws NodeNotFoundException
     */
    public function testCount() {
        $graph = new DictionaryGraph();
        $b = $graph->builder();

        $b->role('A');
        $b->role('B')->assignTo('A');
        $b->role('C')->assignTo('B');
        
        $path = new ListPath($graph);

        $this->assertCount(0, $path);

        $path->pushNode('A');

        $this->assertCount(1, $path);

        $path->pushNode('B');

        $this->assertCount(2, $path);

        $path->pushNode('C');

        $this->assertCount(3, $path);

        $c = $path->popNode();
        $cNode = $graph->getNode('C');

        $this->assertEquals($cNode->getId(), $c->getId());
        $this->assertCount(2, $path);

        $path->popNode();
        $path->popNode();

        $this->assertCount(0, $path);
    }

    /**
     * @throws
     */
    public function testIndexes() {
        $graph = new DictionaryGraph();
        $b = $graph->builder();

        $b->role('A')->assign(
            $b->role('B'),
            $b->role('C')->assignTo('B')
        );
        $b->role('D')->assignTo('C');

        $pathA = new ListPath($graph, ['A','C','D']);
        $pathB = new ListPath($graph, ['A','B','C','D']);
        $pathC = new ListPath($graph, ['B','C']);

        $this->assertTrue($pathA->hasEdge('A','C'));
        $this->assertFalse($pathB->hasEdge('A','C'));

        $this->assertEquals(0, $pathA->getNodeIndex('A'));
        $this->assertEquals(0, $pathB->getNodeIndex('A'));

        $this->assertEquals(2, $pathA->getNodeIndex('D'));
        $this->assertEquals(3, $pathB->getNodeIndex('D'));

        $this->assertEquals(0, $pathC->getNodeIndex('B'));
        $this->assertEquals('C', $pathC->getNodeAt(1)->getName());
    }

}