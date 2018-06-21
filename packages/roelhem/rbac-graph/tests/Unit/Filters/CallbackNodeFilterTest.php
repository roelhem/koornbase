<?php

namespace Unit\Filters;


use Roelhem\RbacGraph\Contracts\MutableNode;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Contracts\Edge;
use Roelhem\RbacGraph\Contracts\NodeFilter;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Filters\CallbackNodeFilter;
use Roelhem\RbacGraph\Graphs\DictionaryGraph;
use Roelhem\RbacGraph\Nodes\SimpleNode;
use Roelhem\RbacGraph\Tests\TestCase;

class CallbackNodeFilterTest extends TestCase
{

    public function testInstance() {
        $graph = new DictionaryGraph();
        $filter = new CallbackNodeFilter($graph, function($node) {
            return true;
        });

        $this->assertInstanceOf(NodeFilter::class, $filter);
        $this->assertInstanceOf(CallbackNodeFilter::class, $filter);
        $this->assertTrue($graph->contains($filter));
    }

    public function testArguments() {
        $graph = new DictionaryGraph();

        $nodeA = $graph->createNode(NodeType::DEFAULT_NODE, 'A');
        $nodeA->setTitle('NodeA');
        $nodeB = $graph->createNode(NodeType::DEFAULT_NODE, 'B');
        $nodeB->setDescription('NodeB');

        $filterO = new CallbackNodeFilter($graph, function($node) {
            return $node !== null;
        });
        $this->assertEquals(CallbackNodeFilter::NODE_TYPE_ORIGINAL, $filterO->getNodeParamType());


        $filterA = new CallbackNodeFilter($graph, function(int $node) use ($nodeA) {
            return $node === $nodeA->getId();
        });
        $this->assertEquals(CallbackNodeFilter::NODE_TYPE_ID, $filterA->getNodeParamType());



        $filterB = new CallbackNodeFilter($graph, function(string $node) use ($nodeA) {
            return $node === $nodeA->getName();
        });
        $this->assertEquals(CallbackNodeFilter::NODE_TYPE_NAME, $filterB->getNodeParamType());



        $filterC = new CallbackNodeFilter($graph, function(Node $node) use ($nodeA) {
            return $node->getTitle() === $nodeA->getTitle();
        });
        $this->assertEquals(CallbackNodeFilter::NODE_TYPE_INSTANCE, $filterC->getNodeParamType());


        $filterD = new CallbackNodeFilter($graph, function(MutableNode $node) use ($nodeB) {
            return $node->getDescription() === $nodeB->getDescription();
        });
        $this->assertEquals(CallbackNodeFilter::NODE_TYPE_INSTANCE, $filterD->getNodeParamType());


        $this->assertTrue($filterO->includeNode($nodeA->getId()));
        $this->assertTrue($filterO->includeNode($nodeB));

        $this->assertTrue($filterA->includeNode($nodeA));
        $this->assertFalse($filterA->includeNode($nodeB));


        $this->assertTrue($filterB($nodeA));
        $this->assertFalse($filterB($nodeB->getName()));


        $this->assertCount(1, $filterC->filter([$nodeA->getId(), $nodeB]));


        $this->assertCount(1, $filterD->filter([$nodeA, $nodeB]));
        $this->assertTrue($filterD->includeNode($nodeB->getId()));
    }

    public function testFilter() {
        $graph = new DictionaryGraph();
        $b = $graph->builder();

        $b->role('R');
        $b->crudAbilities(\Roelhem\RbacGraph\Database\Node::class,
            'crud',
            ['view','create','update','delete']
        )->assignTo('R');

        $b->crudAbilities(\Roelhem\RbacGraph\Database\Edge::class,
            'crud2',
            ['view','create','delete']
        )->assignTo('R');


        $filterA = new CallbackNodeFilter($graph, function(Node $node) {
            return $node->getType() === NodeType::ROLE();
        });

        $filterB = new CallbackNodeFilter($graph, function(Node $node) {
            return $node->getOption('modelClass') === \Roelhem\RbacGraph\Database\Edge::class;
        });

        $this->assertCount(10, $graph->getNodes());
        $this->assertCount(1, $filterA->filter($graph->getNodes()));
        $this->assertCount(4, $filterB->filter($graph->getNodes()));
    }

}