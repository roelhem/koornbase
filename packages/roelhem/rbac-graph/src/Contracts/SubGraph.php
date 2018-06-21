<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 20-06-18
 * Time: 17:16
 */

namespace Roelhem\RbacGraph\Contracts;

/**
 * Contract SubGraph
 *
 * A contract for induced sub-graph.
 *
 * @package Roelhem\RbacGraph\Contracts
 */
interface SubGraph extends Graph, BelongsToGraph
{

    /**
     * Returns if the $node (that exists in the super-graph) also exists in the sub-graph.
     *
     * @param Node|string|integer $node
     * @return boolean
     */
    public function containsNode( $node );

    /**
     * Returns if the $edge (that exists in the super-graph) also exists in the sub-graph.
     *
     * @param Edge $edge
     * @return boolean
     */
    public function containsEdge( $edge );

}