<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 06:22
 */

namespace Roelhem\RbacGraph\Tests\Unit;


use Roelhem\RbacGraph\Contracts\Edge;
use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\MutableGraph;
use Roelhem\RbacGraph\Contracts\MutableNode;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Graphs\DictionaryGraph;
use Roelhem\RbacGraph\Tests\TestCase;

class DictionaryGraphTest extends TestCase
{

    public function testInstance() {

        $graph = new DictionaryGraph();

        $this->assertInstanceOf(Graph::class, $graph);
        $this->assertInstanceOf(MutableGraph::class, $graph);
        $this->assertInstanceOf(DictionaryGraph::class, $graph);

    }

    public function testEmptyInstance() {
        $graph = new DictionaryGraph();

        $this->assertCount(0, $graph->getNodes());
        $this->assertCount(0, $graph->getEdges());

        $this->assertFalse($graph->hasNode('test'));
    }

    public function testNodeCreation() {
        $graph = new DictionaryGraph();

        $this->assertCount(0, $graph->getNodes());

        $role = $graph->createNode(NodeType::ROLE(), 'role');
        $task = $graph->createNode(NodeType::TASK(), 'task');
        $perm = $graph->createNode(NodeType::PERMISSION(), 'permission');

        $this->assertInstanceOf(Node::class, $role);
        $this->assertInstanceOf(MutableNode::class, $role);

        $this->assertCount(3, $graph->getNodes());

        $this->assertTrue($graph->hasNode($role));
        $this->assertTrue($graph->hasNode($task));
        $this->assertTrue($graph->hasNode($perm));

        $this->assertTrue($graph->hasNodeName('role'));
        $this->assertTrue($graph->hasNodeName('task'));
        $this->assertTrue($graph->hasNodeName('permission'));
    }

    public function testEdgeCreation() {
        $graph = new DictionaryGraph();

        $this->assertCount(0, $graph->getEdges());

        $perm = $graph->createNode(NodeType::PERMISSION(), 'permission');
        $set = $graph->createNode(NodeType::PERMISSION_SET(), 'set');

        $edge = $graph->createEdge($set, $perm);

        $this->assertInstanceOf(Edge::class, $edge);

        $this->assertTrue($graph->hasEdge('set','permission'));
        $this->assertFalse($graph->hasEdge('permission','set'));
    }

}