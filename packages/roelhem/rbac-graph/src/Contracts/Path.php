<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 20-06-18
 * Time: 17:58
 */

namespace Roelhem\RbacGraph\Contracts;

use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Exceptions\PathEmptyException;
use Roelhem\RbacGraph\Exceptions\PathIndexException;

/**
 * Contract Path
 *
 * A contract for the data-structure that represents a path in a graph.
 *
 * @package Roelhem\RbacGraph\Contracts
 */
interface Path extends SubGraph, \Countable
{

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  NODES  ----------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the node at the given index
     *
     * @param integer $index
     * @return Node
     * @throws PathIndexException
     */
    public function getNodeAt( $index );

    /**
     * Returns the first node of the path.
     *
     * @return Node
     * @throws PathEmptyException
     */
    public function getFirstNode();

    /**
     * Returns the last node of the path.
     *
     * @return Node
     * @throws PathEmptyException
     */
    public function getLastNode();

    /**
     * Returns the index of the node with the provided id in this path.
     *
     * @param  integer $id
     * @return integer
     * @throws NodeNotFoundException
     */
    public function getNodeIndexById( $id );

    /**
     * Returns the index of the node with the provided name in this path.
     *
     * @param  string $name
     * @return integer
     * @throws NodeNotFoundException
     */
    public function getNodeIndexByName( $name );

    /**
     * Returns the index of the node in this path.
     *
     * @param  Node|string|integer $node
     * @return integer
     * @throws NodeNotFoundException
     */
    public function getNodeIndex( $node );

    /**
     * Returns the Node that comes after the provided Node. Returns null if the provided node is the last Node.
     *
     * @param  Node|string|integer $node
     * @return Node|null
     */
    public function getNextNode( $node );

    /**
     * Returns the Node that came before the provided node. Returns null if the provided node is the first Node.
     *
     * @param Node|string|integer $node
     * @return Node|null
     */
    public function getPrevNode( $node );

    /**
     * Returns the nodes in this path in the right order.
     *
     * (The keys of the path are the index values of the node in the path.)
     *
     * @return Node[]
     */
    public function getNodeList();

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  EDGES  ----------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the outgoing edge of the node with the provided index.
     *
     * @param $index
     * @return Edge
     * @throws PathIndexException
     */
    public function getEdgeAt( $index );

    /**
     * Returns the outgoing edge of the provided node. Returns null if the provided node was the last node of the path.
     *
     * @param Node|string|integer $node
     * @return Edge|null
     * @throws NodeNotFoundException
     */
    public function getOutgoingEdge( $node );

    /**
     * Returns the incoming edge of the provided node. Returns null if the provided node was the first node of the path.
     *
     * @param Node|string|integer $node
     * @return Edge|null
     * @throws NodeNotFoundException
     */
    public function getIncomingEdge( $node );

    /**
     * Returns a list of the edges in this path in the right order.
     *
     * @return Edge[]
     */
    public function getEdgeList();

}