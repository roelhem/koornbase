<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 16:14
 */

namespace Roelhem\RbacGraph\Graphs\Tools\Iterators;


use Roelhem\RbacGraph\Contracts\Nodes\Node;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

class BreathFirstGraphIterator extends AbstractGraphIterator
{

    protected $queue = [];

    public function rewind()
    {
        $this->initVisited();
        $this->queue = [];
        if($this->startNode !== null) {
            array_push($this->queue, $this->startNode->getId());
        }
    }

    /**
     * @throws NodeNotFoundException
     */
    public function next()
    {
        // Unshift the first element in the queue.
        $modelId = array_shift($this->queue);

        // Loop trough all its children.
        $edges = $this->getGraph()->getOutgoingEdges($modelId);
        foreach ($edges as $edge) {
            $childId = $edge->getChildId();
            // Add children that were not visited to the queue.
            if(!in_array($childId, $this->queue) && !$this->isVisited($childId)) {
                array_push($this->queue, $childId);
            }
        }

        // Mark element as visited.
        $this->markVisited($modelId);

        // Handle the empty queue.
        if(count($this->queue) === 0) {
            $nextId = $this->nextNotVisitedId();
            if($nextId !== null) {
                array_push($this->queue, $nextId);
            }
        }
    }

    /**
     * @return Node
     * @throws NodeNotFoundException
     */
    public function current()
    {
        return $this->getGraph()->getNode($this->key());
    }

    /**
     * @return integer
     */
    public function key()
    {
        return $this->queue[0];
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return count($this->queue) > 0;
    }

}