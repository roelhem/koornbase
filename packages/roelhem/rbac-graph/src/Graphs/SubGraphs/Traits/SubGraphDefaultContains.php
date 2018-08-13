<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 00:38
 */

namespace Roelhem\RbacGraph\Graphs\SubGraphs\Traits;


use Roelhem\RbacGraph\Contracts\BelongsToGraph;
use Roelhem\RbacGraph\Contracts\Edges\Edge;
use Roelhem\RbacGraph\Contracts\Graphs\Graph;
use Roelhem\RbacGraph\Contracts\Nodes\Node;

trait SubGraphDefaultContains
{
    /** @return Graph */
    abstract public function getGraph();

    /**
     * @param Node $node
     * @return true
     */
    abstract public function containsNode( $node );

    /**
     * @param Edge $egde
     * @return true
     */
    abstract public function containsEdge( $egde );

    /**
     * Returns if this graph is able to contain the provided $other object.
     *
     * @param mixed $other
     * @return bool
     */
    public function contains($other) {

        if($this === $other) {
            return true;
        }

        if($other instanceof BelongsToGraph) {

            if(!($this->getGraph()->contains($other))) {
                return false;
            }

            if($other instanceof Node) {
                return $this->containsNode($other);
            }

            if($other instanceof Edge) {
                return $this->containsEdge($other);
            }

            return true;
        }

        return false;
    }

}