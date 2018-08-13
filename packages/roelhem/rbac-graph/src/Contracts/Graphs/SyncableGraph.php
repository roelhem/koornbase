<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-06-18
 * Time: 15:57
 */

namespace Roelhem\RbacGraph\Contracts\Graphs;


interface SyncableGraph extends Graph
{

    /**
     * Synchronizes this graph with the other $graph.
     *
     * @param Graph $graph
     * @return mixed
     */
    public function syncWith(Graph $graph);

}