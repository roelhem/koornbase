<?php

namespace Roelhem\RbacGraph\Traits;


use Roelhem\RbacGraph\Contracts\Graphs\Graph;

/**
 * Trait HasSuperGraphProperty
 *
 * Adds a protected property named $graph to keep track of the graph where this object belongs to.
 *
 * @package Roelhem\RbacGraph\Graphs\SubGraphs\Traits
 */
trait HasGraphProperty
{

    /**
     * @var Graph
     */
    protected $graph;

    /**
     * @param $graph
     */
    protected function initGraph( $graph ) {
        if($this->graph !== null) {
            throw new \LogicException('The graph of this object is already set.');
        }

        if(!($graph instanceof Graph)) {
            throw new \InvalidArgumentException('The provided parameter is not an instance of $graph.');
        }

        $this->graph = $graph;
    }

    /**
     * @return Graph
     */
    public function getGraph() {
        return $this->graph;
    }

}