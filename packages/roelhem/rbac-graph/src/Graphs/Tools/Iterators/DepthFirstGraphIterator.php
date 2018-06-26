<?php

namespace Roelhem\RbacGraph\Graphs\Tools\Iterators;


use Roelhem\RbacGraph\Contracts\Nodes\Node;
use Roelhem\RbacGraph\Exceptions\GraphCycleException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;


class DepthFirstGraphIterator extends AbstractGraphIterator
{

    protected $stack = [];

    /**
     * @return bool
     */
    public function valid()
    {
        return count($this->stack) > 0;
    }

    /**
     * @return Node
     * @throws NodeNotFoundException
     */
    public function current()
    {
        return $this->graph->getNode($this->key());
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return end($this->stack);
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        // Check the children of the current node.
        $edges = $this->getGraph()->getOutgoingEdges($this->key());
        foreach ($edges as $edge) {

            $childId = $edge->getChildId();

            // Detect if there is a cycle in the graph.
            if(in_array($childId, $this->stack)) {
                $childName = $edge->getChildName();
                throw new GraphCycleException("A cycle was detected in the graph. Cycle contains node '$childName'.");
            }

            // Add one child if it is not marked visited yet.
            if(!$this->isVisited($childId)) {
                $this->stack[] = $childId;
                return;
            }
        }

        // All children were visited, so mark the current node visited and remove it from the stack.
        $nodeId = array_pop($this->stack);
        $this->markVisited($nodeId);

        // Populate the stack with the next value.
        if(count($this->stack) > 0) {
            $this->next();
        } else {
            $next = $this->nextNotVisitedId();
            if($next !== null) {
                array_push($this->stack, $next);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        $this->initVisited();
        $this->stack = [];
        if($this->startNode !== null) {
            $this->stack[] = $this->startNode->getId();
        }
    }

}