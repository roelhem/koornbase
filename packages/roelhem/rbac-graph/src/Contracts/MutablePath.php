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
     * Adds the provided edge to the end of the path.
     *
     * @param Edge $edge
     * @return void
     * @throws PathWrongEdgeException
     */
    public function pushEdge( $edge );

}