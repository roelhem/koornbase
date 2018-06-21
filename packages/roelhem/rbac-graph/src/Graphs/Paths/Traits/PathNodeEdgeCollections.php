<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 04:46
 */

namespace Roelhem\RbacGraph\Graphs\Paths\Traits;


use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Contracts\Edge;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

trait PathNodeEdgeCollections
{

    /**
     * Returns the incoming edge of the provided node. Returns null if the provided node was the first node of the path.
     *
     * @param Node|string|integer $node
     * @return Edge|null
     * @throws NodeNotFoundException
     */
    abstract public function getIncomingEdge($node);

    /**
     * Returns all the incoming edges of a specific node.
     *
     * @param Node|string|integer $node
     * @return Collection|Edge[]
     * @throws NodeNotFoundException
     */
    public function getIncomingEdges( $node ) {
        $incomingEdge = $this->getIncomingEdge($node);
        if($incomingEdge === null) {
            return collect([]);
        } else {
            return collect([$incomingEdge]);
        }
    }

    /**
     * Returns the outgoing edge of the provided node. Returns null if the provided node was the last node of the path.
     *
     * @param Node|string|integer $node
     * @return Edge|null
     * @throws NodeNotFoundException
     */
    abstract public function getOutgoingEdge($node);

    /**
     * Returns all the outgoing edges of a specific node.
     *
     * @param Node|string|integer $node
     * @return Collection|Edge[]
     * @throws NodeNotFoundException
     */
    public function getOutgoingEdges( $node ) {
        $outgoingEdge = $this->getOutgoingEdge($node);
        if($outgoingEdge === null) {
            return collect([]);
        } else {
            return collect([$outgoingEdge]);
        }
    }

}