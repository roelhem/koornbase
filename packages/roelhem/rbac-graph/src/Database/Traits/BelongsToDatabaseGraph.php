<?php

namespace Roelhem\RbacGraph\Database\Traits;


use Roelhem\RbacGraph\Database\DatabaseGraph;

trait BelongsToDatabaseGraph
{

    /**
     * Returns the graph where this Node belongs to.
     *
     * @return DatabaseGraph
     */
    public function getGraph()
    {
        return resolve(DatabaseGraph::class);
    }

}