<?php

namespace Roelhem\RbacGraph\Graphs\SubGraphs\Traits;


use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Contracts\Edge;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

trait HasInducedEdges
{

    use HasContainsEdgeMethod;

    /**
     * Returns if the parent-side node of the provided edge is in the sub-graph.
     *
     * @param Edge $edge
     * @return boolean
     */
    protected function hasEdgeParent(Edge $edge)
    {
        return $this->containsNode($edge->getParent());
    }

    /**
     * Returns if the child-side node of the provided edge is in the sub-graph.
     *
     * @param Edge $edge
     * @return boolean
     */
    protected function hasEdgeChild(Edge $edge)
    {
        return $this->containsNode($edge->getChild());
    }

    /**
     * Returns if both nodes of the provided edge exist in the sub-graph.
     *
     * @param Edge $edge
     * @return boolean
     */
    protected function hasEdgeEndpoints(Edge $edge)
    {
        return $this->hasEdgeParent($edge) && $this->hasEdgeChild($edge);
    }

    /**
     * Returns if the $edge (that exists in the super-graph) also exists in the sub-graph.
     *
     * @param Edge $edge
     * @return boolean
     */
    public function containsEdge( $edge )
    {
        if($edge instanceof Edge) {
            return $this->hasEdgeEndpoints($edge);
        } else {
            return false;
        }
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
        return $this->hasNode($parent) && $this->hasNode($child) && $this->getGraph()->hasEdge($parent, $child);
    }

    /**
     * Returns all the outgoing edges of the provided node in this sub-graph.
     *
     * @param Node|string|integer $node
     * @return Collection|Edge[]
     * @throws NodeNotFoundException
     */
    public function getOutgoingEdges($node)
    {
        if(!$this->hasNode($node)) {
            throw new NodeNotFoundException("The provided node is not found in this SubGraph.");
        }

        return $this->getGraph()->getOutgoingEdges($node)->filter(function(Edge $edge) {
            return $this->hasEdgeChild($edge);
        });
    }

    /**
     * Returns all the incoming edges of the provided node in this sub-graph.
     *
     * @param Node|string|integer $node
     * @return Collection|Edge[]
     * @throws NodeNotFoundException
     */
    public function getIncomingEdges($node)
    {
        if(!$this->hasNode($node)) {
            throw new NodeNotFoundException("The provided node is not found in this SubGraph.");
        }

        return $this->getGraph()->getIncomingEdges($node)->filter(function(Edge $edge) {
            return $this->hasEdgeParent($edge);
        });
    }

    /**
     * Returns all the children that this node has in this sub-graph.
     *
     * @param Node|string|integer $node
     * @return Collection|Node[]
     * @throws NodeNotFoundException
     */
    public function getChildren($node)
    {
        return $this->getGraph()->getChildren($node)->filter(function($node) {
            return $this->containsNode($node);
        });
    }

    /**
     * Returns all the parents that this node has in this sub-graph.
     *
     * @param Node|string|integer $node
     * @return Collection|Node[]
     * @throws NodeNotFoundException
     */
    public function getParents($node)
    {
        return $this->getGraph()->getParents($node)->filter(function($node) {
            return $this->containsNode($node);
        });
    }


}