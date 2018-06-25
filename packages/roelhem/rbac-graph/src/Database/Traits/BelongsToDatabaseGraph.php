<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 25-06-18
 * Time: 01:36
 */

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