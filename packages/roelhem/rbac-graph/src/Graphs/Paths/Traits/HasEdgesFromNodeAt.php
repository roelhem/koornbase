<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 25-06-18
 * Time: 04:46
 */

namespace Roelhem\RbacGraph\Graphs\Paths\Traits;


use Roelhem\RbacGraph\Contracts\Edge;
use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Exceptions\EdgeNotFoundException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Exceptions\PathIndexException;

trait HasEdgesFromNodeAt
{

    use PathNodeEdgeCollections;

    /**
     * Returns the node at the given index.
     *
     * @param integer $index
     * @return Node
     * @throws PathIndexException
     */
    abstract public function getNodeAt($index);

    /** @return Graph */
    abstract public function getGraph();

    /**
     * Returns the outgoing edge of the node with the provided index.
     *
     * @param $index
     * @return Edge
     * @throws NodeNotFoundException
     * @throws EdgeNotFoundException
     */
    public function getEdgeAt($index)
    {
        return $this->getGraph()->getEdge(
            $this->getNodeAt($index),
            $this->getNodeAt($index + 1)
        );
    }

    /**
     * Returns a list of the edges in this path in the right order.
     *
     * @return Edge[]
     */
    public function getEdgeList()
    {
        $res = [];
        for ($i = 0; $i < $this->count() -1; $i++) {
            $res[$i] = $this->getEdgeAt($i);
        }
        return $res;
    }

    /**
     * Returns a collection of all the edges in this graph.
     *
     * @return Collection|Edge[]
     */
    public function getEdges()
    {
        return collect($this->getEdgeList());
    }

    /**
     * Returns the outgoing edge of the provided node. Returns null if the provided node was the last node of the path.
     *
     * @param Node|string|integer $node
     * @return Edge|null
     * @throws NodeNotFoundException
     */
    public function getOutgoingEdge($node)
    {
        try {
            return $this->getEdgeAt($this->getNodeIndex($node));
        } catch (EdgeNotFoundException|PathIndexException $exception) {
            return null;
        }
    }

    /**
     * Returns the incoming edge of the provided node. Returns null if the provided node was the last node of the path.
     *
     * @param Node|string|integer $node
     * @return Edge|null
     * @throws NodeNotFoundException
     */
    public function getIncomingEdge($node)
    {
        try {
            return $this->getEdgeAt($this->getNodeIndex($node) - 1);
        } catch (EdgeNotFoundException|PathIndexException $exception) {
            return null;
        }
    }

    /**
     * Returns if the $edge (that exists in the super-graph) also exists in the sub-graph.
     *
     * @param Edge $edge
     * @return boolean
     */
    public function containsEdge($edge)
    {
        try {
            $parentIndex = $this->getNodeIndexById($edge->getParentId());
            $childIndex = $this->getNodeIndexById($edge->getChildId());
            return ($parentIndex + 1) === $childIndex;
        } catch (NodeNotFoundException|PathIndexException $exception) {
            return false;
        }
    }

    /**
     * Returns whether or not this graph has a edge between the nodes $parent and $child.
     *
     * @param Node|string|integer $parent An instance, name or id of the searched node.
     * @param Node|string|integer $child An instance, name or id of the searched node.
     * @return boolean
     * @throws NodeNotFoundException
     */
    public function hasEdge($parent, $child)
    {
        $parentIndex = $this->getNodeIndex($parent);
        $childIndex = $this->getNodeIndex($child);

        return ($parentIndex + 1) === $childIndex;
    }

    /**
     * Returns the edge between the nodes $from and $to.
     *
     * @param Edge|string|integer $parent An instance, name or id of the searched node.
     * @param Edge|string|integer $child An instance, name or id of the searched node.
     * @return Edge
     * @throws EdgeNotFoundException
     * @throws NodeNotFoundException
     */
    public function getEdge($parent, $child)
    {
        if($this->hasEdge($parent, $child)) {
            return $this->getGraph()->getEdge($parent, $child);
        } else {
            throw new EdgeNotFoundException("Can't find the edge in this Path.");
        }
    }

}