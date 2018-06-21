<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 05:01
 */

namespace Roelhem\RbacGraph\Nodes\Traits;


use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\AdjacencyNode;
use Roelhem\RbacGraph\Contracts\Edge;
use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Exceptions\EdgeNotFoundException;
use Roelhem\RbacGraph\Exceptions\EdgeNotUniqueException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Exceptions\WrongGraphException;

trait HasAdjacencyLists
{

    /**
     * @var Edge[]
     */
    protected $incomingEdges = [];

    /**
     * @var Edge[]
     */
    protected $outgoingEdges = [];

    /**
     * @return Graph
     */
    abstract public function getGraph();

    /**
     * @param Edge $edge
     * @throws WrongGraphException
     * @throws EdgeNotUniqueException
     */
    public function addEdge(Edge $edge) {
        if($this->getGraph()->contains($edge)) {
            $id = $this->getId();
            if($edge->getParentId() === $id) {

                $this->addOutgoingEdge($edge);

            } elseif ($edge->getChildId() === $id) {

                $this->addIncomingEdge($edge);

            }
        } else {
            throw new WrongGraphException;
        }
    }

    /**
     * @param Edge $edge
     * @throws EdgeNotUniqueException
     */
    protected function addOutgoingEdge(Edge $edge) {
        $childId = $edge->getChildId();
        if(!array_key_exists($childId, $this->outgoingEdges)) {
            $this->outgoingEdges[$childId] = $edge;

            $child = $edge->getChild();
            if(($child instanceof AdjacencyNode) && method_exists($child, 'addEdge')) {
                $child->addEdge($edge);
            }

        } else {
            $oldEdge = $this->outgoingEdges[$childId];
            if(($oldEdge instanceof Edge) && $oldEdge !== $edge) {
                throw new EdgeNotUniqueException;
            }
        }
    }

    /**
     * @param Edge $edge
     * @throws EdgeNotUniqueException
     */
    protected function addIncomingEdge(Edge $edge) {
        $parentId = $edge->getParentId();
        if(!array_key_exists($parentId, $this->incomingEdges)) {
            $this->incomingEdges[$parentId] = $edge;

            $parent = $edge->getParent();
            if(($parent instanceof AdjacencyNode) && method_exists($parent, 'addEdge')) {
                $parent->addEdge($edge);
            }

        } else {
            $oldEdge = $this->incomingEdges[$parentId];
            if(($oldEdge instanceof Edge) && $oldEdge !== $edge) {
                throw new EdgeNotUniqueException;
            }
        }
    }


    /**
     * @return Collection|Edge[]
     */
    public function getIncomingEdges() {
        return collect($this->incomingEdges)->flatten()->filter(function($edge) {
            return $edge instanceof Edge;
        })->values();
    }

    /**
     * @return Collection|Edge[]
     */
    public function getParents() {
        return $this->getIncomingEdges()->map(function(Edge $edge) {
            return $edge->getParent();
        });
    }

    /**
     * @param Node|string|integer $from
     * @return Edge
     * @throws EdgeNotFoundException
     * @throws NodeNotFoundException
     */
    public function getEdgeFrom( $from ) {
        $fromId = $this->getGraph()->getNodeId($from);
        if(array_key_exists($fromId, $this->incomingEdges)) {
            $edge = $this->incomingEdges[$fromId];
            if($edge instanceof Edge) {
                return $edge;
            }

            $fromName = $this->getGraph()->getNodeName($from);
            $toName = $this->getGraph()->getNodeName($this);
            throw new EdgeNotFoundException("Can't find an edge from '$fromName' to the current node '$toName'. The key '$fromId' existed in the incoming-edges array, but the value wasn't an instance of Edge.");
        }

        $fromName = $this->getGraph()->getNodeName($from);
        $toName = $this->getGraph()->getNodeName($this);
        throw new EdgeNotFoundException("Can't find an edge from '$fromName' to the current node '$toName'.");
    }

    /**
     * @param Node|string|integer $from
     * @return bool
     * @throws NodeNotFoundException
     */
    public function hasEdgeFrom( $from ) {
        $fromId = $this->getGraph()->getNodeId($from);
        if(array_key_exists($fromId, $this->incomingEdges)) {
            if($this->incomingEdges[$fromId] instanceof Edge) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return Collection|Edge[]
     */
    public function getOutgoingEdges() {
        return collect($this->outgoingEdges)->flatten()->filter(function($edge) {
            return $edge instanceof Edge;
        })->values();
    }

    /**
     * @return Collection|Edge[]
     */
    public function getChildren() {
        return $this->getOutgoingEdges()->map(function(Edge $edge) {
            return $edge->getChild();
        });
    }
    /**
     * @param Node|string|integer $to
     * @return Edge
     * @throws EdgeNotFoundException
     * @throws NodeNotFoundException
     */
    public function getEdgeTo( $to ) {
        $toId = $this->getGraph()->getNodeId($to);
        if(array_key_exists($toId, $this->outgoingEdges)) {
            $edge = $this->outgoingEdges[$to];
            if($edge instanceof Edge) {
                return $edge;
            }

            $fromName = $this->getGraph()->getNodeName($this);
            $toName = $this->getGraph()->getNodeName($to);
            throw new EdgeNotFoundException("Can't find an edge from the current node '$fromName' to the node '$toName'. The key '$toId' existed in the outgoing-edges array, but the value wasn't an instance of Edge.");
        }

        $fromName = $this->getGraph()->getNodeName($this);
        $toName = $this->getGraph()->getNodeName($to);
        throw new EdgeNotFoundException("Can't find an edge from the current node '$fromName' to the node '$toName'.");
    }

    /**
     * @param Node|string|integer $to
     * @return bool
     * @throws NodeNotFoundException
     */
    public function hasEdgeTo( $to ) {
        $fromId = $this->getGraph()->getNodeId($to);
        if(array_key_exists($fromId, $this->incomingEdges)) {
            if($this->incomingEdges[$fromId] instanceof Edge) {
                return true;
            }
        }
        return false;
    }

}