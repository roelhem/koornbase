<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 04:07
 */

namespace Roelhem\RbacGraph\Contracts;


use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Exceptions\EdgeNotFoundException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

interface AdjacencyNode extends Node
{

    /**
     * Returns a collection of all the incoming edges of this node.
     *
     * @return Collection|Edge[]
     */
    public function getIncomingEdges();

    /**
     * Returns a collection of all the parent nodes of this node.
     *
     * @return Collection|Node[]
     */
    public function getParents();

    /**
     * Returns the edge to this node which is from the provided node.
     *
     * @param Node|string|integer $node  An instance, name or id of the provided node.
     * @return Edge
     * @throws NodeNotFoundException
     * @throws EdgeNotFoundException
     */
    public function getEdgeFrom( $node );

    /**
     * Returns if there is an edge to this array from the provided node.
     *
     * @param Node|string|integer $node  An instance, name or id of the provided node.
     * @return boolean
     * @throws NodeNotFoundException
     */
    public function hasEdgeFrom( $node );

    /**
     * Returns a collection of all the outgoing edges of this node.
     *
     * @return Collection|Edge[]
     */
    public function getOutgoingEdges();

    /**
     * Returns a collection of all the child nodes of this node.
     *
     * @return Collection|Node[]
     */
    public function getChildren();

    /**
     * Returns the edge from this node to the provided node.
     *
     * @param Node|string|integer $node  An instance, name or id of the provided node.
     * @return Edge
     * @throws NodeNotFoundException
     * @throws EdgeNotFoundException
     */
    public function getEdgeTo( $node );

    /**
     * Returns if there exists an edge from this node to the provided node.
     *
     * @param Node|string|integer $node  An instance, name or id of the provided node.
     * @return boolean
     * @throws NodeNotFoundException
     */
    public function hasEdgeTo( $node );

}