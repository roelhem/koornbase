<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 06:22
 */

namespace Roelhem\RbacGraph\Tests\Unit;


use Roelhem\RbacGraph\Contracts\Edges\Edge;
use Roelhem\RbacGraph\Contracts\Graphs\Graph;
use Roelhem\RbacGraph\Contracts\Graphs\MutableGraph;
use Roelhem\RbacGraph\Contracts\Nodes\MutableNode;
use Roelhem\RbacGraph\Contracts\Nodes\Node;
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

    public function testEquals() {
        $graph = new DictionaryGraph();
        $b = $graph->builder();
        $A = $b->role('A')->getNode();
        $B = $b->role('B')->getNode();

        $A_NAME = $A->getName();
        $B_NAME = $B->getName();

        $A_ID = $A->getId();
        $B_ID = $B->getId();

        $A_ITEMS = ['a_instance' => $A, 'a_name' => $A_NAME, 'a_id' => $A_ID];
        $B_ITEMS = ['b_instance' => $B, 'b_name' => $B_NAME, 'b_id' => $B_ID];

        foreach ($A_ITEMS as $label1 => $a1) {
            foreach ($A_ITEMS as $label2 => $a2) {
                $this->assertTrue($graph->nodeEquals($a1, $a2), "TESTING $label1 EQUALS $label2");
            }
        }

        foreach ($B_ITEMS as $label1 => $b1) {
            foreach ($A_ITEMS as $label2 => $a2) {
                $this->assertFalse($graph->nodeEquals($b1, $a2), "TESTING $label1 EQUALS $label2");
            }
        }

        foreach ($B_ITEMS as $label1 => $b1) {
            foreach ($B_ITEMS as $label2 => $b2) {
                $this->assertTrue($graph->nodeEquals($b1, $b2), "TESTING $label1 EQUALS $label2");
            }
        }

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

    public function testBuilder() {
        $graph = new DictionaryGraph();
        $b = $graph->builder();

        $b->role('A');
        $b->role('B');
        $roleC = $b->role('C')->assign('A','B');
        $b->permission('x')->assignTo($roleC);

        $this->assertCount(4, $graph->getNodes());
        $this->assertCount(3, $graph->getEdges());

        $this->assertTrue($graph->hasEdge('C','A'));
        $this->assertTrue($graph->hasEdge('C', 'B'));
        $this->assertFalse($graph->hasEdge('A','x'));
        $this->assertTrue($graph->hasEdge('C','x'));
    }

}