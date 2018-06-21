<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 04:05
 */

namespace Roelhem\RbacGraph\Contracts;


use Roelhem\RbacGraph\Exceptions\EdgeNotFoundException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Exceptions\PathWrongEdgeException;

interface MutablePath extends Path
{

    /**
     * Adds the provided node to the end of the path.
     *
     * @param Node|string|integer $node
     * @return void
     * @throws NodeNotFoundException
     * @throws EdgeNotFoundException
     */
    public function pushNode( $node );

    /**
     * Removes the last node from the path and returns it.
     *
     * @return Node
     */
    public function popNode();

    /**
     * Adds the provided node to the beginning of the path.
     *
     * @param Node|string|integer $node
     * @return void
     * @throws NodeNotFoundException
     * @throws EdgeNotFoundException
     */
    public function unshiftNode( $node );

    /**
     * Removes the first node from the path and returns it.
     *
     * @return Node
     */
    public function shiftNode();

    /**
     * Adds the provided edge to the end of the path.
     *
     * @param Edge $edge
     * @return void
     * @throws PathWrongEdgeException
     */
    public function pushEdge( $edge );

}