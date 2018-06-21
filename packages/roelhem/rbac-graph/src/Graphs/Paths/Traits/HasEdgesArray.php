<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 05:40
 */

namespace Roelhem\RbacGraph\Graphs\Paths\Traits;


use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Edge;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Exceptions\EdgeNotFoundException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Exceptions\PathIndexException;

trait HasEdgesArray
{

    use PathNodeEdgeCollections;

    /**
     * Stores the edges in order.
     *
     * @var Edge[]
     */
    protected $edges = [];

    /**
     * Returns a list of the edges in this path in the right order.
     *
     * @return Edge[]
     */
    public function getEdgeList()
    {
        return $this->edges;
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
     * Returns if the $edge (that exists in the super-graph) also exists in the sub-graph.
     *
     * @param Edge $edge
     * @return boolean
     */
    public function containsEdge($edge)
    {
        $parentId = $edge->getParentId();
        $childId = $edge->getChildId();
        foreach ($this->edges as $otherEdge) {
            if($parentId === $otherEdge->getParentId() && $childId === $otherEdge->getChildId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns the outgoing edge of the node with the provided index.
     *
     * @param $index
     * @return Edge
     * @throws PathIndexException
     */
    public function getEdgeAt($index)
    {
        if(!array_key_exists(intval($index), $this->edges)) {
            throw new PathIndexException("Can't find the edge for index $index.");
        }
        return $this->edges[intval($index)];
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
            $index = $this->getNodeIndex($node);
            return $this->getEdgeAt($index);
        } catch (PathIndexException $exception) {
            return null;
        }
    }

    /**
     * Returns the incoming edge of the provided node. Returns null if the provided node was the first node of the path.
     *
     * @param Node|string|integer $node
     * @return Edge|null
     * @throws NodeNotFoundException
     */
    public function getIncomingEdge($node)
    {
        try {
            $index = $this->getNodeIndex($node);
            return $this->getEdgeAt($index - 1);
        } catch (PathIndexException $exception) {
            return null;
        }
    }

    /**
     * Returns whether or not this graph has a edge between the nodes $parent and $child.
     *
     * @param Node|string|integer $parent      An instance, name or id of the searched node.
     * @param Node|string|integer $child       An instance, name or id of the searched node.
     * @return boolean
     * @throws NodeNotFoundException
     */
    public function hasEdge($parent, $child)
    {
        $index = $this->getNodeIndex($parent);
        try {
            $edge = $this->getEdgeAt($index);

            if($child instanceof Node) {
                $child = $this->getNodeId($edge);
            }

            if(is_integer($child)) {
                return $child === $edge->getChildId();
            }

            if(is_string($child)) {
                return $child === $edge->getChildName();
            }

            return false;

        } catch (PathIndexException $exception) {
            return false;
        }
    }

    /**
     * Returns the edge between the nodes $from and $to.
     *
     * @param Node|string|integer $parent      An instance, name or id of the searched node.
     * @param Node|string|integer $child       An instance, name or id of the searched node.
     * @return Edge
     * @throws EdgeNotFoundException
     * @throws NodeNotFoundException
     */
    public function getEdge($parent, $child)
    {
        $index = $this->getNodeIndex($parent);
        try {
            $edge = $this->getEdgeAt($index);

            if($child instanceof Node) {
                $child = $this->getNodeId($edge);
            }

            if(is_integer($child) && $child === $edge->getChildId()) {
                return $edge;
            } elseif(is_string($child) && $child === $edge->getChildName()) {
                return $edge;
            }

            throw new EdgeNotFoundException("The searched edge does not exist in this path.");

        } catch (PathIndexException $exception) {
            throw new EdgeNotFoundException("The searched edge does not exist in this path.", 0, $exception);
        }
    }

}