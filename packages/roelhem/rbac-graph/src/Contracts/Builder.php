<?php

namespace Roelhem\RbacGraph\Contracts;


use Roelhem\RbacGraph\Exceptions\NodeNameNotUniqueException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Exceptions\NodeTypeNotFoundException;

interface Builder extends Graph
{

    /**
     * Returns the NodeBuilder with the provided name.
     *
     * First, it will search for the name, then it will search for the name with the current prefix.
     *
     * @param string $name
     * @return NodeBuilder|null
     */
    public function find(string $name);

    /**
     * Same as the `find(string $name)` method, but throws an exception if no `NodeBuilder` was found.
     *
     * @param string $name
     * @return NodeBuilder
     * @throws NodeNotFoundException
     */
    public function get(string $name);

    /**
     * Creates a new `NodeBuilder` for a node with type $type and name $name.
     *
     * @param int $type
     * @param string $name
     * @return NodeBuilder
     * @throws NodeTypeNotFoundException
     * @throws NodeNameNotUniqueException
     */
    public function create(int $type, string $name);

    /**
     * Returns the `NodeBuilder` for the node with name $name and `NodeType` $type. Creates a new `NodeBuilder`
     * if the node does not exists yet.
     *
     * @param int $type
     * @param string $name
     * @return NodeBuilder
     * @throws NodeTypeNotFoundException
     * @throws NodeNameNotUniqueException
     */
    public function node(int $type, string $name);

    /**
     * Creates or edits the role with the provided name.
     *
     * @param string $name
     * @return NodeBuilder
     */
    public function role(string $name);

    /**
     * Creates or edits the permission with the provided name.
     *
     * @param string $name
     * @return NodeBuilder
     */
    public function permission(string $name);

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
     */
    public function edge($parent, $child);

    /**
     * Build the rbac-structure of this RbacBuilder.
     *
     * @return Graph
     */
    public function build();

}