<?php

namespace Roelhem\RbacGraph\Graphs\Tools\Iterators\Traits;

use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Graphs\Graph;
use Roelhem\RbacGraph\Contracts\Nodes\Node;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

trait HasVisitedGraphNodes
{

    /**
     * @var bool[]
     */
    protected $visited;

    /**
     * @return Graph
     */
    abstract protected function getGraph();

    /**
     * Initializes
     *
     * @return void
     */
    protected function initVisited() {
        $this->visited = $this->getGraph()->getNodes()->mapWithKeys(function(Node $node) {
            return [$node->getId() => false];
        })->all();
    }

    /**
     * @param Node|string|integer $node
     * @throws NodeNotFoundException
     */
    protected function markVisited( $node )
    {
        $nodeId = $this->getGraph()->getNodeId($node);
        $this->visited[$nodeId] = true;
    }

    /**
     * Returns the id of the next node in the graph that is not yet visited. Returns null if all nodes were visited.
     *
     * @return null|integer
     */
    protected function nextNotVisitedId() {
        foreach ($this->visited as $id => $isVisited) {
            if(!$isVisited) {
                return $id;
            }
        }
        return null;
    }

    /**
     * Returns the next node in the graph that is not yet visited. Returns null if all nodes were visited.
     *
     * @return Node|null
     * @throws NodeNotFoundException
     */
    protected function nextNotVisited() {
        $id = $this->nextNotVisitedId();
        if($id !== null) {
            return $this->getGraph()->getNodeById($id);
        } else {
            return null;
        }
    }

    /**
     * @param Node|string|integer $node
     * @return bool
     * @throws NodeNotFoundException
     */
    public function isVisited( $node )
    {
        $nodeId = $this->getGraph()->getNodeId($node);
        return array_get($this->visited, $nodeId, false);
    }

    /**
     * Returns a collection of all the nodes that were visited up-to this point.
     *
     * @return Collection|Node[]
     */
    public function getVisited() {
        return collect($this->visited)->filter(function($value) {
            return $value;
        })->map(function($value, $key) {
            return $this->getGraph()->getNode($key);
        });
    }

    /**
     * Returns a collection of all the nodes that are not market visited at this point.
     *
     * @return Collection|Node[]
     */
    public function getNotVisited() {
        return collect($this->visited)->filter(function($value) {
            return !$value;
        })->map(function($value, $key) {
            return $this->getGraph()->getNode($key);
        });
    }

}