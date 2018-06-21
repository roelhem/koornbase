<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 21:43
 */

namespace Roelhem\RbacGraph\Contracts;


use Roelhem\RbacGraph\Enums\NodeType;

interface MutableGraph extends Graph
{

    /**
     * Returns a builder instance to build this graph.
     *
     * @return Builder
     */
    public function builder();

    // ------------------------------------------------------------------------------------------------------------ //
    // ---------  NODES  ------------------------------------------------------------------------------------------ //
    // ------------------------------------------------------------------------------------------------------------ //

    /**
     * Creates a new node for this MutableGraph and adds the newly created node to the graph.
     *
     * @param integer|NodeType $type
     * @param string $name
     * @param array $options
     * @return Node
     */
    public function createNode( $type, $name, $options = []);

    /**
     * Adds a new node to this MutableGraph based on the provided $node.
     *
     * @param Node $node
     * @return Node
     */
    public function addNode(Node $node);

    // ------------------------------------------------------------------------------------------------------------ //
    // ---------  EDGES  ------------------------------------------------------------------------------------------ //
    // ------------------------------------------------------------------------------------------------------------ //

    /**
     * Creates a new edge for this MutableGraph and adds the newly created edge to the graph.
     *
     * @param Node|string|integer $parent
     * @param Node|string|integer $child
     * @return Edge
     */
    public function createEdge( $parent, $child );

    /**
     * Adds a new edge to this MutableGraph based on the provided $node.
     *
     * @param Edge $edge
     * @return Edge
     */
    public function addEdge(Edge $edge);

}