<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-06-18
 * Time: 01:04
 */

namespace Roelhem\RbacGraph\Contracts;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Exceptions\EdgeNotAllowedException;
use Roelhem\RbacGraph\Exceptions\NodeNameNotUniqueException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

/**
 * Contract RbacService
 * @package Roelhem\RbacGraph\Contracts
 */
interface RbacService extends BuilderShortcuts
{

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  GRAPH METHODS  --------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the graph that is used for the default authorization.
     *
     * @return Graph
     */
    public function graph();

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  BUILDER METHODS  ------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the Builder instance used by this RbacService.
     *
     * @return Builder
     */
    public function builder();

    /**
     * Gets the node with a specific name from the Rbac-structure inside the Builder.
     *
     * @param string $name
     * @return NodeBuilder
     * @throws NodeNotFoundException
     */
    public function get(string $name);

    /**
     * Creates a new `NodeBuilder` for a node with the provided type and name.
     *
     * @param integer|NodeType $type
     * @param string $name
     * @param array $options
     * @return NodeBuilder
     * @throws NodeNameNotUniqueException
     */
    public function create($type, string $name, array $options = []);

    /**
     * Returns the `NodeBuilder` for the node with name $name and `NodeType` $type. Creates a new `NodeBuilder`
     * if the node does not exists yet.
     *
     * @param integer|NodeType $type
     * @param string $name
     * @param array $options
     * @return NodeBuilder
     * @throws NodeNameNotUniqueException
     */
    public function node($type, string $name, array $options = []);


    /**
     * Runs all the builder commands within the callable object. The names of all the nodes created within this
     * callable will be prepended with the $prefix string.
     *
     * @param string $prefix
     * @param callable $definitions
     */
    public function group(string $prefix, callable $definitions);

    /**
     * Defines a edge or returns the already existing edge.
     *
     * @param Node|string|integer $parent
     * @param Node|string|integer $child
     * @return Edge
     * @throws NodeNotFoundException
     * @throws EdgeNotAllowedException
     */
    public function edge($parent, $child);

}