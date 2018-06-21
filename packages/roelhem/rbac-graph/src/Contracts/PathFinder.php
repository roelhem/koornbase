<?php

namespace Roelhem\RbacGraph\Contracts;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

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
     * @throws NodeNotFoundException
     */
    public function exists( $start, $end );

    /**
     * Returns the amount of paths between the start and end nodes.
     *
     * @param Node|string|integer $start
     * @param Node|string|integer $end
     * @return boolean
     * @throws NodeNotFoundException
     */
    public function count( $start, $end );

    /**
     * Returns a path between the provided start and end nodes.
     *
     * @param Node|string|integer $start
     * @param Node|string|integer $end
     * @return Path|null
     * @throws NodeNotFoundException
     */
    public function find( $start, $end );

    /**
     * Returns all the paths between the provided start and end nodes.
     *
     * @param Node|string|integer $start
     * @param Node|string|integer $end
     * @return array|Path[]
     * @throws NodeNotFoundException
     */
    public function findAll( $start, $end );

}