<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 12-06-18
 * Time: 16:37
 */

namespace Roelhem\RbacGraph\Contracts;
use Illuminate\Support\Collection;
use Roelhem\RbacGraph\Exceptions\EdgeNotFoundException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;


/**
 * Interface Graph
 *
 * For all the objects that models a graph.
 *
 * @package Roelhem\RbacGraph\Contracts
 */
interface Graph
{

    /**
     * Returns if this graph can be regarded as equal to the object in the parameter.
     *
     * @param mixed $other
     * @return boolean
     */
    public function equals( $other ) : bool ;

    // ------------------------------------------------------------------------------------------------------------ //
    // ---------  RETRIEVING OBJECTS  ----------------------------------------------------------------------------- //
    // ------------------------------------------------------------------------------------------------------------ //

    /**
     * Returns a collection of all the nodes in this graph.
     *
     * @return Collection|Node[]
     */
    public function getNodes();

    /**
     * Returns a collection of all the edges in this graph.
     *
     * @return Collection|Edge[]
     */
    public function getEdges();

    // ------------------------------------------------------------------------------------------------------------ //
    // ---------  CHECKING FOR EXISTENCE  ------------------------------------------------------------------------- //
    // ------------------------------------------------------------------------------------------------------------ //

    /**
     * Returns whether or not this graph has a node with the provided $id.
     *
     * @param integer $id
     * @return boolean
     */
    public function hasNodeId( $id );

    /**
     * Returns whether or not this graph has a node with the provided $name.
     *
     * @param string $name
     * @return boolean
     */
    public function hasNodeName( $name );

    /**
     * Returns whether or not this graph has a node from the provided $node parameter.
     *
     * @param Node|string|integer $node      An instance, name or id of the searched node.
     * @return boolean
     */
    public function hasNode( $node );

    // ------------------------------------------------------------------------------------------------------------ //
    // ---------  GETTING NODE INSTANCES  ------------------------------------------------------------------------- //
    // ------------------------------------------------------------------------------------------------------------ //

    /**
     * Returns the node with the provided id.
     *
     * @param integer $id
     * @return Node
     * @throws NodeNotFoundException
     */
    public function getNodeById( $id );

    /**
     * Returns the node with the provided name.
     *
     * @param string $name
     * @return Node
     * @throws NodeNotFoundException
     */
    public function getNodeByName( $name );

    /**
     * Returns the node based on the given $node parameter.
     *
     * @param Node|string|integer $node      An instance, name or id of the searched node.
     * @return Node
     * @throws NodeNotFoundException
     */
    public function getNode( $node );

    // ------------------------------------------------------------------------------------------------------------ //
    // ---------  GETTING NODE PROPERTIES  ------------------------------------------------------------------------ //
    // ------------------------------------------------------------------------------------------------------------ //

    /**
     * Returns the id of the node that was referenced by the $node parameter.
     *
     * @param Node|string|integer $node      An instance, name or id of the searched node.
     * @return integer
     * @throws NodeNotFoundException
     */
    public function getNodeId( $node );

    /**
     * Returns the name of the node that was referenced by the $node parameter.
     *
     * @param Node|string|integer $node      An instance, name or id of the searched node.
     * @return string
     * @throws NodeNotFoundException
     */
    public function getNodeName( $node );

    // ------------------------------------------------------------------------------------------------------------ //
    // ---------  GETTING EDGES  ---------------------------------------------------------------------------------- //
    // ------------------------------------------------------------------------------------------------------------ //

    /**
     * Returns whether or not this graph has a edge between the nodes $parent and $child.
     *
     * @param Node|string|integer $parent      An instance, name or id of the searched node.
     * @param Node|string|integer $child       An instance, name or id of the searched node.
     * @return boolean
     * @throws NodeNotFoundException
     */
    public function hasEdge( $parent, $child );

    /**
     * Returns the edge between the nodes $from and $to.
     *
     * @param Node|string|integer $parent      An instance, name or id of the searched node.
     * @param Node|string|integer $child       An instance, name or id of the searched node.
     * @return Edge
     * @throws EdgeNotFoundException
     * @throws NodeNotFoundException
     */
    public function getEdge( $parent, $child );

    /**
     * Returns all the outgoing edges of a specific node
     *
     * @param Node|string|integer $node      An instance, name or id of the searched node.
     * @return Collection|Edge[]
     * @throws NodeNotFoundException
     */
    public function getOutgoingEdges( $node );

    /**
     * Returns all the incoming edges of a specific node
     *
     * @param Node|string|integer $node      An instance, name or id of the searched node.
     * @return Collection|Edge[]
     * @throws NodeNotFoundException
     */
    public function getIncomingEdges( $node );

    /**
     * Returns all the children of a specific node
     *
     * @param $node
     * @return Collection|Edge[]
     * @throws NodeNotFoundException
     */
    public function getChildren( $node );

    /**
     * Returns all the parents of a specific node
     *
     * @param $node
     * @return Collection|Edge[]
     * @throws NodeNotFoundException
     */
    public function getParents( $node );



}