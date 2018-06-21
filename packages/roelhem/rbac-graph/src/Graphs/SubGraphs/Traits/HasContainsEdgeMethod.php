<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 00:57
 */

namespace Roelhem\RbacGraph\Graphs\SubGraphs\Traits;


use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Edge;
use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Exceptions\EdgeNotFoundException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

trait HasContainsEdgeMethod
{

    /**
     * @return Graph
     */
    abstract public function getGraph();

    /**
     * Returns if the $edge (that exists in the super-graph) also exists in the sub-graph.
     *
     * @param Edge $edge
     * @return boolean
     */
    abstract public function containsEdge( $edge );

    /**
     * Returns all the edges of this sub-graph.
     *
     * @return Collection|Edge[]
     */
    public function getEdges()
    {
        return $this->getGraph()->getEdges()->filter(function(Edge $edge) {
            return $this->containsEdge($edge);
        });
    }

    /**
     * Returns if there exists an edge from the $parent node to the $child node.
     *
     * @param Node|string|integer $parent
     * @param Node|string|integer $child
     * @return bool
     * @throws NodeNotFoundException
     */
    public function hasEdge($parent, $child)
    {
        try {
            $edge = $this->getGraph()->getEdge($parent, $child);
            return $this->containsEdge($edge);
        } catch (EdgeNotFoundException $exception) {
            return false;
        }
    }

    /**
     * Gets the edge from $parent to $child.
     *
     * @param Node|string|integer $parent
     * @param Node|string|integer $child
     * @return Edge
     * @throws NodeNotFoundException
     * @throws EdgeNotFoundException
     */
    public function getEdge($parent, $child)
    {
        $edge = $this->getGraph()->getEdge($parent, $child);
        if($this->containsEdge($edge)) {
            return $edge;
        } else {
            throw new EdgeNotFoundException("Can't find the edge in this sub-graph. (The edge does exist in the super-graph.)");
        }
    }

    /**
     * Returns all the outgoing edges of the provided node in this sub-graph.
     *
     * @param Node|string|integer $node
     * @return Collection|Edge[]
     * @throws NodeNotFoundException
     */
    public function getOutgoingEdges( $node )
    {
        return $this->getGraph()->getOutgoingEdges( $node )->filter(function($edge) {
            return $this->containsEdge($edge);
        });
    }

    /**
     * Returns all the incoming edges of the provided node in this sub-graph.
     *
     * @param Node|string|integer $node
     * @return Collection|Edge[]
     * @throws NodeNotFoundException
     */
    public function getIncomingEdges( $node )
    {
        return $this->getGraph()->getIncomingEdges( $node )->filter(function($edge) {
            return $this->containsEdge($edge);
        });
    }
}