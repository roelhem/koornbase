<?php

namespace Roelhem\RbacGraph\Contracts;

/**
 * Contract PathFinder
 *
 * An contract for service-classes which are able to find paths between two nodes in a graph.
 *
 * @package Roelhem\RbacGraph\Contracts
 */
interface PathFinder extends BelongsToGraph
{

    /**
     * Returns if there exists a path between the start and end nodes.
     *
     * @param Node|string|integer $start
     * @param Node|string|integer $end
     * @return boolean
     */
    public function exists( $start, $end );

    /**
     * Returns the amount of paths between the start and end nodes.
     *
     * @param Node|string|integer $start
     * @param Node|string|integer $end
     * @return boolean
     */
    public function count( $start, $end );

    /**
     * Returns a path between the provided start and end nodes.
     *
     * @param Node|string|integer $start
     * @param Node|string|integer $end
     * @return Path|null
     */
    public function find( $start, $end );

    /**
     * Returns all the paths between the provided start and end nodes.
     *
     * @param Node|string|integer $start
     * @param Node|string|integer $end
     * @return Path[]
     */
    public function findAll( $start, $end );

}