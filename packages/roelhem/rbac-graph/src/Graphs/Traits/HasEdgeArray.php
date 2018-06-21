<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 01:39
 */

namespace Roelhem\RbacGraph\Contracts\Traits;


use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Edge;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Exceptions\EdgeNotFoundException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Exceptions\WrongGraphException;

trait HasEdgeArray
{

    use GraphDefaultContains;
    use GraphHasEdgeFromGetterMethods;
    use GraphRelatedFromEdgeMethods;

    protected $edges;

    /**
     * @param Edge $edge
     *
     * @throws WrongGraphException
     */
    protected function storeEdge( Edge $edge ) {
        if($this->contains($edge)) {
            $this->edges[] = $edge;
        } else {
            throw new WrongGraphException;
        }
    }

    /**
     * Returns all the edges in this graph.
     *
     * @return Collection|Edge[]
     */
    public function getEdges() {
        return collect($this->edges)->flatten()->filter(function($edge) {
            return $edge instanceof Edge;
        })->unique(function(Edge $edge) {
            return $edge->getParentId().'->'.$edge->getChildId();
        })->values();
    }

    /**
     * @param Node|string|integer $parent
     * @param Node|string|integer $child
     * @return Edge
     * @throws EdgeNotFoundException
     * @throws NodeNotFoundException
     */
    public function getEdge( $parent, $child ) {
        $parentId = $this->getNodeId($parent);
        $childId = $this->getNodeId($child);
        foreach (collect($this->edges)->flatten() as $edge) {
            if(($edge instanceof Edge) && $edge->getParentId() === $parentId && $edge->getChildId() === $childId) {
                return $edge;
            }
        }
        $parentName = $this->getNodeName($parent);
        $childName = $this->getNodeName($child);
        throw new EdgeNotFoundException("Couldn't find an edge from '$parentName' to '$childName'.");
    }

    /**
     * @param Node|string|integer $node
     * @return Collection|Edge[]
     */
    public function getOutgoingEdges( $node ) {
        $nodeId = $this->getNodeId( $node );
        return $this->getEdges()->filter(function(Edge $edge) use ($nodeId) {
            return $edge->getParentId() === $nodeId;
        });
    }

    /**
     * @param Node|string|integer $node
     * @return Collection|Edge[]
     */
    public function getIncomingEdges( $node ) {
        $nodeId = $this->getNodeId( $node );
        return $this->getEdges()->filter(function(Edge $edge) use ($nodeId) {
            return $edge->getChildId() === $nodeId;
        });
    }

}