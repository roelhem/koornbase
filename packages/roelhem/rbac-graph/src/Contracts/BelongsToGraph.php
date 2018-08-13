<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 20-06-18
 * Time: 20:13
 */

namespace Roelhem\RbacGraph\Contracts;

use Roelhem\RbacGraph\Contracts\Graphs\Graph;

/**
 * Interface BelongsToGraph
 *
 * For every object that belongs to a graph.
 *
 * @package Roelhem\RbacGraph\Contracts
 */
interface BelongsToGraph
{

    /**
     * Gives the graph where this object belongs to.
     *
     * @return Graph
     */
    public function getGraph();

}