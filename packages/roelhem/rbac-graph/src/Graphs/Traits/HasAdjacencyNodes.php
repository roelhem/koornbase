<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 04:16
 */

namespace Roelhem\RbacGraph\Graphs\Traits;


use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Nodes\AdjacencyNode;
use Roelhem\RbacGraph\Contracts\Edges\Edge;
use Roelhem\RbacGraph\Contracts\Nodes\Node;
use Roelhem\RbacGraph\Exceptions\EdgeNotFoundException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

trait HasAdjacencyNodes
{

    /**
     * Returns all the edges of this Graph.
     *
     * @return Collection|Edge[]
     */
    public function getEdges() {
        $res = collect($this->getNodes())->filter(function($node) {
            return $node instanceof AdjacencyNode;
        })->map(function(AdjacencyNode $node) {
            return collect($node->getOutgoingEdges())->filter(function($edge) {
                return $edge instanceof Edge;
            })->unique(function(Edge $edge) {
                return $edge->getChildId();
            });
        })->flatten();

        if(!($res instanceof Collection)) {
            $res = collect($res);
        }
        return $res->values();
    }

    /**
     * Returns the outgoing edges of the provided node.
     *
     * @param Node|string|integer $node
     * @throws NodeNotFoundException
     * @return Collection|Edge[]
     */
    public function getOutgoingEdges( $node ) {
        $node = $this->getNode($node);
        if($node instanceof AdjacencyNode) {
            return $node->getOutgoingEdges();
        } else {
            return collect([]);
        }
    }

    /**
     * @param Node|string|integer $node
     * @return Collection|Node[]
     * @throws NodeNotFoundException
     */
    public function getChildren( $node ) {
        $node = $this->getNode($node);
        if ($node instanceof AdjacencyNode) {
            return $node->getChildren();
        } else {
            return collect([]);
        }
    }

    /**
     * Returns the incoming edges of the provided node.
     *
     * @param Node|string|integer $node
     * @throws NodeNotFoundException
     * @return Collection|Edge[]
     */
    public function getIncomingEdges( $node ) {
        $node = $this->getNode($node);
        if($node instanceof AdjacencyNode) {
            return $node->getIncomingEdges();
        } else {
            return collect([]);
        }
    }

    /**
     * @param Node|string|integer $node
     * @return Collection|Node[]
     * @throws NodeNotFoundException
     */
    public function getParents( $node ) {
        $node = $this->getNode($node);
        if ($node instanceof AdjacencyNode) {
            return $node->getParents();
        } else {
            return collect([]);
        }
    }

    /**
     * Returns the edge from the $from node to the $to node.
     *
     * @param Node|string|integer $from
     * @param Node|string|integer $to
     * @return Edge
     * @throws NodeNotFoundException
     * @throws EdgeNotFoundException
     */
    public function getEdge( $from, $to ) {
        $from = $this->getNode($from);
        if($from instanceof AdjacencyNode) {
            return $from->getEdgeTo($to);
        }

        $to = $this->getNode($to);
        if($to instanceof AdjacencyNode) {
            return $to->getEdgeFrom($from);
        }

        $fromName = $this->getNodeName($from);
        $toName = $this->getNodeName($to);
        throw new EdgeNotFoundException("Can't find the edge from node '$fromName' to node '$toName' they both aren't AdjacencyNode instances.");

    }

    /**
     * @param Node|string|integer $from
     * @param Node|string|integer $to
     * @return bool
     * @throws NodeNotFoundException
     */
    public function hasEdge( $from, $to ) {
        $from = $this->getNode($from);
        if($from instanceof AdjacencyNode) {
            return $from->hasEdgeTo($to);
        }

        $to = $this->getNode($to);
        if($to instanceof AdjacencyNode) {
            return $to->hasEdgeFrom($from);
        }

        return false;
    }

}